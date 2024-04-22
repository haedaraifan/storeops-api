<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionExpenseRequest;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    private function getTransactionType(string $type): TransactionType
    {
        return TransactionType::where("name", $type)->first();
    }

    private function getTransactionStatus(string $status): TransactionStatus
    {
        return TransactionStatus::where("name", $status)->first();
    }

    public function expense(TransactionExpenseRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();
        $transactionType = $this->gettransactionType("Pengeluaran");
        $transactionStatus = $this->getTransactionStatus($data["status"]);

        $transaction = new Transaction($data);
        $transaction->user_id = $user->id;
        $transaction->status_id = $transactionStatus->id;
        $transaction->type_id = $transactionType->id;
        $transaction->save();

        return response()->json([
            "message" => "Transaksi berhasil dicatat."
        ])->setStatusCode(200);
    }
}
