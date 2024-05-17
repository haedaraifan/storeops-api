<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionResponseHelper;
use App\Http\Requests\TransactionExpenseRequest;
use App\Http\Requests\TransactionIncomeRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    private function getTransactionType(string $type): TransactionType
    {
        return TransactionType::whereName($type)->first();
    }

    private function getTransactionStatus(string $status): TransactionStatus
    {
        return TransactionStatus::whereName($status)->first();
    }

    public function expense(TransactionExpenseRequest $request): JsonResponse
    {
        $data = $request->validated();
        $transactionType = $this->getTransactionType("Pengeluaran");
        $transactionStatus = $this->getTransactionStatus($data["status"]);

        $transaction = new Transaction($data);
        $transaction->status_id = $transactionStatus->id;
        $transaction->type_id = $transactionType->id;
        $transaction->save();

        return response()->json([
            "message" => "Transaksi berhasil dicatat."
        ])->setStatusCode(201);
    }

    public function income(TransactionIncomeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $transaction = new Transaction($data);
        $transactionType = $this->gettransactionType("Penjualan");
        $transactionStatus = $this->getTransactionStatus($data["status"]);
        $transactionProducts = [];
        $transaction->selling_price = 0;

        foreach($data["products"] as $productRequest) {
            $product = Product::whereId($productRequest["id"])->first();

            if($productRequest["quantity"] > $product->quantity) {
                ExceptionResponseHelper::throwInvariantError("Kuantitas lebih banyak dari stok produk.");
            }

            $transaction->selling_price += $product->selling_price;
            $product->quantity -= $productRequest["quantity"];
            $product->save();

            $transactionProduct = new TransactionProduct([
                "name" => $product->name,
                "price" => $product->selling_price,
                "quantity" => $productRequest["quantity"]
            ]);
            array_push($transactionProducts, $transactionProduct);
        }

        $transaction->status_id = $transactionStatus->id;
        $transaction->type_id = $transactionType->id;
        $transaction->save();

        foreach($transactionProducts as $transactionProduct) {
            $transactionProduct->transaction_id = $transaction->id;
            $transactionProduct->save();
        }

        return response()->json([
            "message" => "Transaksi berhasil dicatat."
        ])->setStatusCode(201);
    }

    public function list(Request $request): JsonResponse
    {
        $user = auth()->user();
        $transactions = Transaction::where("user_id", $user->id)->get();

        return (TransactionResource::collection($transactions))->response()->setStatusCode(200);
    }
}
