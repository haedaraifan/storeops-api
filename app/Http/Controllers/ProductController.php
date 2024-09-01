<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionResponseHelper;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductImportRequest;
use App\Http\Requests\ProductRestockRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductRecapResource;
use App\Http\Resources\ProductResctokRecapResource;
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

    private function getProductUnit(?string $unitName): ?ProductUnit
    {
        if (is_null($unitName) || trim($unitName) === '') {
            return null;
        }

        return ProductUnit::whereName($unitName)->first();
    }

    private function generateFileName($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = Carbon::now()->format("ymdHisu") . '.' . $extension;
        return $fileName;
    }

    public function create(ProductCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $unit = $this->getProductUnit($data["unit"] ?? null);
        $product = new Product($data);

        if($request->hasFile("image")) {
            $imageUrl = $this->sendProductImage($request->file("image"));
            $product->image = $imageUrl;
        }

        $product->unit_id = $unit->id ?? null;
        $product->save();

        $addProductHistory = new AddProductHistory($product->toArray());
        $addProductHistory->product_id = $product->id;
        $addProductHistory->unit = $unit->name ?? null;
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
        $search = $request->query("search");
        $category = $request->query("category");
        $stock = $request->query("stock", "all");
        $isPaginate = $request->query("paginate", "true");
        $query = Product::query();

        if($category) {
            $query->where("category", "like", "%{$category}%");
        }

        if($search) {
            $query->where("name", "like", "%{$search}%");
        }

        switch($stock) {
            case "high":
                $query->where("quantity", '>', 50);
                break;
            case "low";
                $query->where("quantity", '<', 51);
                break;
            case "empty";
                $query->where("quantity", 0);
                break;
            default;
                break;
        }

        $query->orderBy("created_at", "desc");
        $products = $isPaginate === "false" ? $query->get() : $query->paginate(10);

        return (ProductResource::collection($products))->response()->setStatusCode(200);
    }

    public function update(int $productId, ProductUpdateRequest $request): ProductResource
    {
        $data = $request->validated();
        $product = $this->getProduct($productId);
        $unit = $this->getProductUnit($data["unit"] ?? null);

        $product->fill($data);
        if($request->hasFile("image")) {
            $imageUrl = $this->sendProductImage($request->file("image"));
            $product->image = $imageUrl;
        }
        $product->unit_id = $unit->id ?? null;
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
        $restockProductHistory->unit = $product->unit->name ?? null;
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
            $addProductHistory->unit = $product->unit->name ?? null;
            $addProductHistory->save();
        }

        return response()->json([
            "message" => "Data berhasil ditambahkan.",
            "imported" => $importedProducts
        ])->setStatusCode(201);
    }

    public function recap(Request $request): JsonResponse
    {
        $search = $request->query("search", '');
        $category = $request->query("category", '');
        $year = $request->query("year", Carbon::now()->year);
        $month = $request->query("month", Carbon::now()->month);
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        if($month != Carbon::now()->month || $year != Carbon::now()->year) {
            $productRecap = ProductsRecap::whereDate("date", $endDate)
                ->select("product_id as id", "name", "category", "first_quantity", "last_quantity", "incoming_quantity", "outgoing_quantity")
                ->where("name", "like", "%{$search}%")
                ->where("category", "like", "%{$category}%")
                ->get();
        } else {
            $productRecap = $this->getCurrentMonthRecap($search, $category, $startDate, $endDate, Carbon::create($year, $month, 1)->subMonth());
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

    private function getCurrentMonthRecap($search, $category, $startDate, $endDate, $previousMonth)
    {
        $products = Product::where("name", "like", "%{$search}%")
            ->where("category", "like", "%{$category}%")
            ->get();
        $firstQuantity = ProductsRecap::selectRaw("product_id AS id, name, last_quantity AS quantity")
            ->whereDate("date", $previousMonth->endOfMonth())
            ->get();
        $outgoingQuantity = TransactionProduct::selectRaw("product_id AS id, name, SUM(quantity) as quantity")
            ->whereHas("transaction", function ($query) use ($startDate, $endDate) {
                $query->whereBetween("date", [$startDate, $endDate]);
            })
            ->groupBy("product_id", "name")
            ->get();
        $incomingQuantity = RestockProductHistory::selectRaw("product_id AS id, name, SUM(quantity) as quantity")
            ->whereBetween("date", [$startDate, $endDate])
            ->groupBy("product_id", "name")
            ->get();

        return $products->map(function($product) use ($firstQuantity, $outgoingQuantity, $incomingQuantity) {
            $first = $firstQuantity->firstWhere("id", $product->id);
            $outgoing = $outgoingQuantity->firstWhere("id", $product->id);
            $incoming = $incomingQuantity->firstWhere("id", $product->id);

            return [
                "id" => $product->id,
                "name" => $product->name,
                "category" => $product->category,
                "first_quantity" => $first->quantity ?? 0,
                "last_quantity" => $product->quantity,
                "incoming_quantity" => $incoming->quantity ?? 0,
                "outgoing_quantity" => $outgoing->quantity ?? 0
            ];
        });
    }

    public function detailRecap(Request $request, int $productId): JsonResponse
    {
        $product = $this->getProduct($productId);

        $transactionProducts = TransactionProduct::with("transaction")
            ->where("product_id", $productId)
            ->orderByDesc("transaction_id")
            ->get();

        $transactionRecap = $transactionProducts->map(function($transactionProduct) {
            return [
                "transaction_id" => $transactionProduct->transaction->id,
                "invoice" => $transactionProduct->transaction->invoice,
                "date" => $transactionProduct->transaction->date->isoFormat("dddd, D MMMM Y"),
                "quantity" => $transactionProduct->quantity
            ];
        });

        $restockRecap = RestockProductHistory::where("product_id", $productId)->get();

        return response()->json([
            "data" => [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $product->quantity,
                "unit" => $product->unit,
                "category" => $product->category,
                "transactions" => $transactionRecap,
                "restock" => ProductResctokRecapResource::collection($restockRecap)
            ]
        ]);
    }
}
