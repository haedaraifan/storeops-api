<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private function getProduct(User $user, int $productId): Product
    {
        $product = Product::where("id", $productId)->where("user_id", $user->id)->first();
        if(!$product) {
            throw new HttpResponseException(response()->json([
                "error" => "Produk tidak ditemukan."
            ])->setStatusCode(404));
        }
        return $product;
    }

    private function getCategory(string $categoryName): Category
    {
        return Category::where("name", $categoryName)->first();
    }

    public function create(ProductCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();
        $category = $this->getCategory($data["category"]);

        $product = new Product($data);
        $product->user_id = $user->id;
        $product->category_id = $category->id;
        $product->save();

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function get(int $productId): ProductResource
    {
        $user = Auth::user();
        $product = $this->getProduct($user, $productId);

        return new ProductResource($product);
    }

    public function list(Request $request): JsonResponse
    {
        $user = Auth::user();
        $products = Product::where("user_id", $user->id)->get();

        return (ProductResource::collection($products))->response()->setStatusCode(200);
    }

    public function update(int $productId, ProductUpdateRequest $request): ProductResource
    {
        $user = Auth::user();
        $data = $request->validated();
        $product = $this->getProduct($user, $productId);
        $category = $this->getCategory($data["category"]);

        $product->fill($data);
        $product->category_id = $category->id;
        $product->save();

        return new ProductResource($product);
    }
}
