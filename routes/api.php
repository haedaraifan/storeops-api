<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/users/register", [UserController::class, "register"]);
Route::post("/users/login", [UserController::class, "login"]);

Route::middleware(ApiAuthMiddleware::class)->group(function() {
    Route::get("/users/me", [UserController::class, "get"]);
    Route::patch("/users/me", [UserController::class, "update"]);
    Route::delete("/users/logout", [UserController::class, "logout"]);

    Route::post("/products", [ProductController::class,"create"]);
    Route::get("/products", [ProductController::class,"list"]);
    Route::get("/products/{productId}", [ProductController::class,"get"])->where("productId", "[0-9]+");
});
