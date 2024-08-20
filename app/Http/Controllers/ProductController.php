<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionResponseHelper;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductImportRequest;
use App\Http\Requests\ProductRestockRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductRecapResource;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\AddProductHistory;
use App\Models\Product;
use App\Models\ProductsRecap;
use App\Models\ProductUnit;
use App\Models\RestockProductHistory;
use App\Models\TransactionProduct;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    private function sendProductImage($image): string
    {
        $fileName = $this->generateFileName($image);
        $imagePath = $image->storeAs("images", $fileName, "public");
        $imageUrl = asset('storage/' . $imagePath);
        return $imageUrl;
    }

    private function getProduct(int $productId): Product
    {
        $product = Product::whereId($productId)->first();
        if(!$product) {
            ExceptionResponseHelper::throwNotFoundError("Produk tidak ditemukan.");
        }
        return $product;
    }

    private function getProductUnit(string $unitName): ProductUnit
    {
        return ProductUnit::whereName($unitName)->first();
    }

    private function generateFileName($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = now()->format("ymdHisu") . '.' . $extension;
        return $fileName;
    }

    public function create(ProductCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $unit = $this->getProductUnit($data["unit"]);
        $product = new Product($data);

        if($request->hasFile("image")) {
            $imageUrl = $this->sendProductImage($request->file("image"));
            $product->image = $imageUrl;
        }

        $product->unit_id = $unit->id;
        $product->save();

        $addProductHistory = new AddProductHistory($product->toArray());
        $addProductHistory->product_id = $product->id;
        $addProductHistory->unit = $product->unit->name;
        $addProductHistory->save();

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function get(int $productId): ProductResource
    {
        $product = $this->getProduct($productId);

        return new ProductResource($product);
    }

    public function list(Request $request): JsonResponse
    {
        $category = $request->query("category");
        $isPaginate = $request->query("paginate", "true");
        $query = Product::query();

        if($category) {
            $query->where(function($q) use ($category) {
                $q->where("category", "like", '%' . $category . '%');
            });
        }
        $query->orderBy("created_at", "desc");
        $products = $isPaginate === "false" ? $query->get() : $query->paginate(10);

        return (ProductResource::collection($products))->response()->setStatusCode(200);
    }

    public function update(int $productId, ProductUpdateRequest $request): ProductResource
    {
        $data = $request->validated();
        $product = $this->getProduct($productId);
        $unit = $this->getProductUnit($data["unit"]);

        $product->fill($data);
        if($request->hasFile("image")) {
            $imageUrl = $this->sendProductImage($request->file("image"));
            $product->image = $imageUrl;
        }
        $product->unit_id = $unit->id;
        $product->save();

        return new ProductResource($product);
    }

    public function delete(int $productId): JsonResponse
    {
        $product = $this->getProduct($productId);

        $product->delete();

        return response()->json([
            "message" => "Produk berhasil dihapus."
        ])->setStatusCode(200);
    }

    public function restock(int $product_id, ProductRestockRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = $this->getProduct($product_id);

        $product->increment("quantity", $data['quantity']);
        $product->save();

        $restockProductHistory = new RestockProductHistory($data);
        $restockProductHistory->product_id = $product->id;
        $restockProductHistory->name = $product->name;
        $restockProductHistory->unit = $product->unit->name;
        $restockProductHistory->category = $product->category;
        $restockProductHistory->purchase_price = $product->purchase_price;
        $restockProductHistory->selling_price = $product->selling_price;
        $restockProductHistory->save();

        return response()->json([
            "message" => "Produk berhasil direstok."
        ])->setStatusCode(200);
    }

    public function import(ProductImportRequest $request): JsonResponse
    {
        $request->validated();
        $import = new ProductsImport();

        Excel::import($import, $request->file("products"));

        $importedProducts = $import->getImportedProducts();
        foreach($importedProducts as $product) {
            $addProductHistory = new AddProductHistory($product->toArray());
            $addProductHistory->product_id = $product->id;
            $addProductHistory->unit = $product->unit->name;
            $addProductHistory->save();
        }

        return response()->json([
            "message" => "Data berhasil ditambahkan.",
            "imported" => $importedProducts
        ])->setStatusCode(201);
    }

    // baru bisa rekap data pada bulan sekarang
    public function recap(Request $request): JsonResponse
    {
        $year = $request->query("year", now()->year);
        $month = $request->query("month", now()->month);
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        if($month != now()->month || $year != now()->year) {
            $productRecap = ProductsRecap::whereDate("date", $endDate)
                ->select("product_id as id", "name", "image", "first_quantity", "last_quantity", "incoming_quantity", "outgoing_quantity")
                ->get();
        } else {
            $productRecap = $this->getCurrentMonthRecap($startDate, $endDate);
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paginatedRecap = new LengthAwarePaginator(
            $productRecap->forPage($currentPage, $perPage),
            $productRecap->count(),
            $perPage,
            $currentPage,
            ["path" => $request->url(), "query" => $request->query()]
        );

        $resource = [
            "range" => Carbon::create($year, $month, 1)->isoFormat("MMMM Y"),
            "products" => ProductRecapResource::collection($paginatedRecap),
        ];

        return response()->json([
            "data" => $resource,
            "links" => [
                "first" => $paginatedRecap->url(1),
                "last" => $paginatedRecap->url($paginatedRecap->lastPage()),
                "prev" => $paginatedRecap->previousPageUrl(),
                "next" => $paginatedRecap->nextPageUrl(),
            ],
            "meta" => [
                "current_page" => $paginatedRecap->currentPage(),
                "from" => $paginatedRecap->firstItem(),
                "last_page" => $paginatedRecap->lastPage(),
                "links" => $paginatedRecap->linkCollection(),
                "path" => $paginatedRecap->path(),
                "per_page" => $paginatedRecap->perPage(),
                "to" => $paginatedRecap->lastItem(),
                "total" => $paginatedRecap->total(),
            ],
        ])->setStatusCode(200);
    }

    private function getCurrentMonthRecap($startDate, $endDate)
    {
        $products = Product::get();
        $firstQuantity = AddProductHistory::selectRaw("product_id AS id, name, quantity")
            ->whereBetween("date", [$startDate, $endDate])
            ->get();
        $outgoingQuantity = TransactionProduct::selectRaw("product_id AS id, name, SUM(quantity) as quantity")
            ->whereHas("transaction", function ($query) use ($startDate, $endDate) {
                $query->whereBetween("date", [$startDate, $endDate]);
            })
            ->groupBy("product_id", "name")
            ->get();
        $incomingQuantity = RestockProductHistory::selectRaw("product_id AS id, name, SUM(quantity) as quantity")
            ->whereBetween("created_at", [$startDate, $endDate])
            ->groupBy("product_id", "name")
            ->get();

        return $products->map(function($product) use ($firstQuantity, $outgoingQuantity, $incomingQuantity) {
            $first = $firstQuantity->firstWhere("id", $product->id);
            $outgoing = $outgoingQuantity->firstWhere("id", $product->id);
            $incoming = $incomingQuantity->firstWhere("id", $product->id);

            return [
                "id" => $product->id,
                "name" => $product->name,
                "first_quantity" => $first->quantity ?? 0,
                "last_quantity" => $product->quantity,
                "incoming_quantity" => $incoming->quantity ?? 0,
                "outgoing_quantity" => $outgoing->quantity ?? 0
            ];
        });
    }
}
