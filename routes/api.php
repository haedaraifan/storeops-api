<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::post("/login", [UserController::class, "login"]);
Route::post("/send", [UserController::class, "sendToWeb"]);

Route::middleware(ApiAuthMiddleware::class)->group(function() {
    Route::get("/me", [UserController::class, "get"]);
    Route::patch("/me", [UserController::class, "update"]);
    Route::delete("/logout", [UserController::class, "logout"]);

    Route::middleware("role:Admin")->group(function() {
        Route::post("/register", [UserController::class, "register"]);

        Route::post("/products", [ProductController::class,"create"]);
        Route::post("/products/{productId}", [ProductController::class,"update"])->where("productId", "[0-9]+");
        Route::delete("/products/{productId}", [ProductController::class,"delete"])->where("productId", "[0-9]+");
        Route::post("/products/{productId}/restock", [ProductController::class, "restock"])->where("productId", "[0-9]+");
        Route::post("/products/import", [ProductController::class,"import"]);
    });

    Route::middleware("role:Kasir")->group(function() {
        Route::post("/transactions/expense", [TransactionController::class, "expense"]);
        Route::post("/transactions/income", [TransactionController::class, "income"]);
        Route::post("/transactions/status/{transactionId}", [TransactionController::class, "updateStatus"])->where("transactionId", "[0-9]+");
        Route::post("/transactions/finish/{transactionId}", [TransactionController::class, "finish"])->where("transactionId", "[0-9]+");
    });

    Route::get("/transactions", [TransactionController::class, "list"]);
    Route::get("/transactions/income", [TransactionController::class, "listIncome"]);
    Route::get("/transactions/income/{transactionId}", [TransactionController::class, "detailIncome"])->where("transactionId", "[0-9]+");
    Route::get("/transactions/income/statistic", [TransactionController::class, "statistic"]);
    Route::post("/transactions/{transactionId}/checklist", [TransactionController::class, "checklistProduct"])->where("transactionId", "[0-9]+");

    Route::get("/products", [ProductController::class,"list"]);
    Route::get("/products/{productId}", [ProductController::class,"get"])->where("productId", "[0-9]+");
    Route::get("/products/histories/add", [HistoryController::class, "listAddedProduct"]);
    Route::get("/products/histories/restock", [HistoryController::class, "listRestockedProduct"]);
    Route::get("/products/recap", [ProductController::class, "recap"]);

    Route::post("/notes", [NoteController::class, "create"]);
    Route::get("/notes", [NoteController::class, "list"]);
    Route::get("/notes/{noteId}", [NoteController::class, "get"])->where("noteId", "[0-9]+");
    Route::post("/notes/{noteId}", [NoteController::class, "update"])->where("noteId", "[0-9]+");
    Route::delete("/notes/{noteId}", [NoteController::class, "delete"])->where("noteId", "[0-9]+");
});
