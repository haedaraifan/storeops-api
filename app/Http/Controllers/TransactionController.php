<?php

namespace App\Http\Controllers;

use App\Helpers\DateFormatHelper;
use App\Helpers\ExceptionResponseHelper;
use App\Helpers\SendNotificationHelper;
use App\Http\Requests\TransactionExpenseRequest;
use App\Http\Requests\TransactionIncomeRequest;
use App\Http\Requests\TransactionStatusUpdateRequest;
use App\Http\Requests\UpdateChecklistProductRequest;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\TransactionIncomeStatisticResource;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    private function getTransaction(int $transactionId): Transaction
    {
        $transaction = Transaction::whereId($transactionId)->first();
        if(!$transaction) {
            ExceptionResponseHelper::throwNotFoundError("Transaksi tidak ditemukan.");
        }
        return $transaction;
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

            $transaction->selling_price += $product->selling_price * $productRequest["quantity"];
            $product->quantity -= $productRequest["quantity"];
            $product->save();

            $transactionProduct = new TransactionProduct([
                "product_id" => $product->id,
                "name" => $product->name,
                "price" => $product->selling_price,
                "quantity" => $productRequest["quantity"],
                "option_id" => $productRequest["option"] ?? 1,
                "is_checked" => $productRequest["is_checked"] ?? false
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

        SendNotificationHelper::toMobileApp("Ada transaksi baru!", "ini body");

        return response()->json([
            "message" => "Transaksi berhasil dicatat."
        ])->setStatusCode(201);
    }

    public function list(Request $request): JsonResponse
    {
        $isPaginate = $request->query("paginate", "true");
        $transactions = Transaction::orderBy("date", "desc");
        $transactions = $isPaginate === "false" ? $transactions->get() : $transactions->paginate(10);

        return (TransactionResource::collection($transactions))->response()->setStatusCode(200);
    }

    public function listIncome(Request $request): JsonResponse
    {
        $search = $request->query("search");
        $range = $request->query("range", "all");
        $paid = $request->query("paid", "all");
        $isPaginate = $request->query("paginate", "true");
        $incomeType = TransactionType::whereName("Penjualan")->first();
        $query = Transaction::whereTypeId($incomeType->id)->with(["products.option"]);

        switch($range) {
            case "daily":
                $query->whereDate("date", Carbon::today());
                break;
            case "weekly":
                $query->whereBetween("date", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case "monthly":
                $query->whereMonth("date", Carbon::now()->month)->whereYear("date", Carbon::now()->year);
                break;
            default:
                break;
        }
        switch($paid) {
            case "true":
                $query->whereStatusId($this->getTransactionStatus("Lunas")->id);
                break;
            case "false":
                $query->whereStatusId($this->getTransactionStatus("Belum Lunas")->id);
                break;
            default:
                break;
        }

        if($search) {
            $query->where(function ($q) use ($search) {
                $formattedDate = DateFormatHelper::toEnglishDate($search);
                $q->where("customer_name", "like", "%{$search}%")
                    ->orWhereRaw("DATE_FORMAT(date, '%W, %e %M %Y') LIKE ?", ["%{$formattedDate}%"]);
            });
        }

        $transactions = $query->orderBy("date", "desc");
        $transactions = $isPaginate === "false" ? $transactions->get() : $transactions->paginate(10);

        return (TransactionDetailResource::collection($transactions))->response()->setStatusCode(200);
    }

    public function detailIncome(Request $request, int $transactionId): TransactionDetailResource
    {
        $transaction = $this->getTransaction($transactionId);
        return new TransactionDetailResource($transaction);
    }

    public function updateStatus(TransactionStatusUpdateRequest $request, int $transactionId): JsonResponse
    {
        $data = $request->validated();
        $transaction = $this->getTransaction($transactionId);
        $newTransactionStatus = $this->getTransactionStatus($data["status"]);

        $transaction->status_id = $newTransactionStatus->id;
        $transaction->save();

        return response()->json([
            "message" => "Transaksi berhasil diperbarui."
        ])->setStatusCode(200);
    }

    public function statistic(Request $request): JsonResponse
    {
        $year = $request->query("year", now()->year);
        $month = $request->query("month", now()->month);
        $sort = $request->query("sort");
        $isPaginate = $request->query("paginate", "true");

        $range = Carbon::create($year, $month, 1);
        $recap = TransactionProduct::selectRaw("product_id AS id, name, CAST(SUM(quantity) AS UNSIGNED) as quantity")
            ->whereHas("transaction", fn($query) => $query->whereYear("date", $year)->whereMonth("date", $month))
            ->groupBy("product_id", "name")
            ->orderBy("quantity", $sort == "asc" ? "asc" : "desc");
        $recap = $isPaginate === "false" ? $recap->get() : $recap->paginate(20);

        $resource = new TransactionIncomeStatisticResource([
            "range" => $range->isoFormat("MMMM Y"),
            "products" => $recap
        ]);

        return response()->json([
            "data" => $resource,
            "links" => [
                "first" => $recap->url(1),
                "last" => $recap->url($recap->lastPage()),
                "prev" => $recap->previousPageUrl(),
                "next" => $recap->nextPageUrl(),
            ],
            "meta" => [
                "current_page" => $recap->currentPage(),
                "from" => $recap->firstItem(),
                "last_page" => $recap->lastPage(),
                "links" => $recap->linkCollection(),
                "path" => $recap->path(),
                "per_page" => $recap->perPage(),
                "to" => $recap->lastItem(),
                "total" => $recap->total(),
            ],
        ])->setStatusCode(200);
    }

    public function checklistProduct(UpdateChecklistProductRequest $request, int $transactionId): JsonResponse
    {
        $data = $request->validated();

        TransactionProduct::whereTransactionId($transactionId)
            ->whereIn("product_id", $data["products"])
            ->update([
                "is_checked" => true
            ]);

        return response()->json([
            "message" => "Data produk berhasil diperbarui."
        ])->setStatusCode(200);
    }
}
