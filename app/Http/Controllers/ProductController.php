<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionResponseHelper;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\AddProductHistory;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private function sendProductImage($image): string
    {
        $fileName = $this->generateFileName($image);
        $imagePath = $image->storeAs("images", $fileName, "public");
        $imageUrl = asset('storage/' . $imagePath);
        return $imageUrl;
    }

    private function getProduct(User $user, int $productId): Product
    {
        $product = $user->products()->whereId($productId)->first();
        if(!$product) {
            ExceptionResponseHelper::throwNotFoundError("Produk tidak ditemukan.");
        }
        return $product;
    }

    private function getCategory(string $categoryName): Category
    {
        return Category::whereName($categoryName)->first();
    }

    private function generateFileName($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = now()->format("ymdHisu") . '.' . $extension;
        return $fileName;
    }

    public function create(ProductCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();
        $category = $this->getCategory($data["category"]);
        $product = new Product($data);

        if($request->hasFile("image")) {
            $imageUrl = $this->sendProductImage($request->file("image"));
            $product->image = $imageUrl;
        }

        $product->user_id = $user->id;
        $product->category_id = $category->id;
        $product->save();

        $addProductHistory = new AddProductHistory($product->toArray());
        $addProductHistory->date = now()->format("d F, Y");
        $addProductHistory->user_id = $user->id;
        $addProductHistory->save();

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
        $products = Product::whereUserId($user->id)->get();

        return (ProductResource::collection($products))->response()->setStatusCode(200);
    }

    public function update(int $productId, ProductUpdateRequest $request): ProductResource
    {
        $user = Auth::user();
        $data = $request->validated();
        $product = $this->getProduct($user, $productId);
        $category = $this->getCategory($data["category"]);

        if($request->hasFile("image")) {
            $imageUrl = $this->sendProductImage($request->file("image"));
            $product->image = $imageUrl;
        }

        $product->fill($data);
        $product->category_id = $category->id;
        $product->save();

        return new ProductResource($product);
    }

    public function delete(int $productId): JsonResponse
    {
        $user = Auth::user();
        $product = $this->getProduct($user, $productId);

        $product->delete();

        return response()->json([
            "message"=> "Produk berhasil dihapus."
        ])->setStatusCode(200);
    }
}
