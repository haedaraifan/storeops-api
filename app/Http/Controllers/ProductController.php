<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
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
}
