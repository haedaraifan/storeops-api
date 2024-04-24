<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/register", [UserController::class, "register"]);
Route::post("/login", [UserController::class, "login"]);

Route::middleware(ApiAuthMiddleware::class)->group(function() {
    Route::get("/me", [UserController::class, "get"]);
    Route::post("/me", [UserController::class, "update"]);
    Route::delete("/logout", [UserController::class, "logout"]);

    Route::post("/products", [ProductController::class,"create"]);
    Route::get("/products", [ProductController::class,"list"]);
    Route::get("/products/{productId}", [ProductController::class,"get"])->where("productId", "[0-9]+");
    Route::put("/products/{productId}", [ProductController::class,"update"])->where("productId", "[0-9]+");
    Route::delete("/products/{productId}", [ProductController::class,"delete"])->where("productId", "[0-9]+");

    Route::post("/transactions/expense", [TransactionController::class, "expense"]);
});
