<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create(ProductCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $category = Category::where("name", $data["category"])->first();

        $product = new Product($data);
        $product->user_id = $user->id;
        $product->category_id = $category->id;
        $product->save();

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function get(int $productId): ProductResource
    {
        $user = Auth::user();
        $product = Product::where("id", $productId)->where("user_id", $user->id)->first();

        if(!$product) {
            throw new HttpResponseException(response()->json([
                "error" => "Produk tidak ditemukan."
            ])->setStatusCode(404));
        }

        return new ProductResource($product);
    }

    public function list(Request $request): JsonResponse
    {
        $user = Auth::user();
        $products = Product::where("user_id", $user->id)->get();

        return (ProductResource::collection($products))->response()->setStatusCode(200);
    }
}
