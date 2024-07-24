<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionResponseHelper;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductImportRequest;
use App\Http\Requests\ProductRestockRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\AddProductHistory;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\RestockProductHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $query = Product::query();

        if($category) {
            $query->where(function($q) use ($category) {
                $q->where("category", "like", '%' . $category . '%');
            });
        }
        $products = $query->get();

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

        Excel::import(new ProductsImport, $request->file("products"));

        return response()->json([
            "message" => "Data berhasil ditambahkan."
        ])->setStatusCode(201);
    }
}
