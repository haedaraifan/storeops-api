<?php

namespace App\Http\Controllers;

use App\Events\ProductCheckedEvent;
use App\Helpers\DateFormatHelper;
use App\Helpers\ExceptionResponseHelper;
use App\Helpers\SendNotificationHelper;
use App\Http\Requests\TransactionExpenseRequest;
use App\Http\Requests\TransactionFinishRequest;
use App\Http\Requests\TransactionIncomeRequest;
use App\Http\Requests\TransactionStatusUpdateRequest;
use App\Http\Requests\UpdateChecklistProductRequest;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\TransactionIncomeStatisticResource;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionCustomNominal;
use App\Models\TransactionNominalType;
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

    private function generateInvoiceNumber(): string
    {
        $currentDateTime = Carbon::now()->format("Ymdhis");
        $uuidPart = strtoupper(substr(str_replace('-', '', \Illuminate\Support\Str::uuid()->toString()), 0, 8));
        $baseInvoiceNumber = $currentDateTime . $uuidPart;
        $shuffledInvoiceNumber = str_shuffle($baseInvoiceNumber);
        return substr($shuffledInvoiceNumber, 0, 20);
    }

    // public function expense(TransactionExpenseRequest $request): JsonResponse
    // {
    //     $data = $request->validated();
    //     $transactionType = $this->getTransactionType("Pengeluaran");
    //     $transactionStatus = $this->getTransactionStatus($data["status"]);

    //     $transaction = new Transaction($data);
    //     $transaction->status_id = $transactionStatus->id;
    //     $transaction->type_id = $transactionType->id;
    //     $transaction->save();

    //     return response()->json([
    //         "message" => "Transaksi berhasil dicatat."
    //     ])->setStatusCode(201);
    // }

    public function income(TransactionIncomeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $transaction = new Transaction($data);
        $transactionType = $this->gettransactionType("Penjualan");
        $transactionStatus = $this->getTransactionStatus($data["status"]);
        $transactionProducts = [];
        $totalPrice = 0;

        foreach($data["products"] as $productRequest) {
            $product = Product::whereId($productRequest["id"])->first();

            if($productRequest["quantity"] > $product->quantity) {
                ExceptionResponseHelper::throwInvariantError("Kuantitas lebih banyak dari stok produk.");
            }

            $totalPrice += $product->selling_price * $productRequest["quantity"];
            $product->quantity -= $productRequest["quantity"];
            $product->save();

            $transactionProduct = new TransactionProduct([
                "product_id" => $product->id,
                "name" => $product->name,
                "category" => $product->category,
                "price" => $product->selling_price,
                "quantity" => $productRequest["quantity"],
                "option_id" => $data["option"] ?? 1,
                "is_checked" => $productRequest["is_checked"] ?? false
            ]);
            array_push($transactionProducts, $transactionProduct);
        }

        $transaction->selling_price = $totalPrice;
        $transaction->invoice = $this->generateInvoiceNumber();
        $transaction->status_id = $transactionStatus->id;
        $transaction->type_id = $transactionType->id;
        $transaction->save();

        foreach($data["nominals"] as $nominal) {
            $nominalType = TransactionNominalType::whereName($nominal["name"])->first();
            $transactionCustomNominal = new TransactionCustomNominal($nominal);
            $transactionCustomNominal->transaction_id = $transaction->id;
            $transactionCustomNominal->nominal_type_id = $nominalType->id;
            $transactionCustomNominal->save();

            if($nominal["name"] === "Pengiriman") {
                $totalPrice += $nominal["amount"];
            }
        }

        $transaction->selling_price = $totalPrice;
        $transaction->save();

        foreach($transactionProducts as $transactionProduct) {
            $transactionProduct->transaction_id = $transaction->id;
            $transactionProduct->save();
        }

        SendNotificationHelper::toMobileApp("Transaksi baru atas nama " . $data["customer_name"] ?? "Seseorang", Carbon::now()->isoFormat("dddd, D MMMM YYYY HH:mm"));

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
        $finish = $request->query("finish", "all");
        $isPaginate = $request->query("paginate", "true");
        $from = $request->query("from");
        $to = $request->query("to");

        $incomeType = TransactionType::whereName("Penjualan")->first();
        $query = Transaction::whereTypeId($incomeType->id)->with(["products.option"]);

        DateFormatHelper::validateDateRange(["from" => $from, "to" => $to]);
        if($from && $to) {
            $fromDate = Carbon::parse($from)->startOfDay();
            $toDate = Carbon::parse($to)->endOfDay();
            $query->whereBetween("date", [$fromDate, $toDate]);
        }

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
        switch($finish) {
            case "true":
                $query->whereIsFinished(1);
                break;
            case "false":
                $query->whereIsFinished(0);
                break;
            default:
                break;
        }

        if($search) {
            $query->where(function ($q) use ($search) {
                $formattedDate = DateFormatHelper::toEnglishDate($search);
                $q->where("customer_name", "like", "%{$search}%")
                    ->orWhere("invoice", "like", "%{$search}%")
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
        $category = $request->query("category");
        $year = $request->query("year", Carbon::now()->year);
        $month = $request->query("month", Carbon::now()->month);
        $sort = $request->query("sort");
        $isPaginate = $request->query("paginate", "true");

        $range = Carbon::create($year, $month, 1);
        $recap = TransactionProduct::selectRaw("product_id AS id, name, category, CAST(SUM(quantity) AS UNSIGNED) as quantity")
            ->whereHas("transaction", fn($query) => $query->whereYear("date", $year)->whereMonth("date", $month))
            ->where("category", "like", "%{$category}%")
            ->groupBy("product_id", "name", "category")
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
        $transaction = $this->getTransaction($transactionId);

        TransactionProduct::whereTransactionId($transactionId)
            ->whereIn("product_id", $data["products_id"])
            ->update([
                "is_checked" => true
            ]);

        event(new ProductCheckedEvent($transactionId, $transaction->customer_name));

        return response()->json([
            "message" => "Data produk berhasil diperbarui."
        ])->setStatusCode(200);
    }

    public function finish(TransactionFinishRequest $request, int $transactionId): JsonResponse
    {
        $request->validated();
        $transaction = $this->getTransaction($transactionId);

        if($transaction->status_id !==1) {
            ExceptionResponseHelper::throwInvariantError("Transaksi belum lunas!");
        }

        $transaction->is_finished = true;
        $transaction->save(['touch' => false]);

        return response()->json([
            "message" => "Transaksi berhasil diperbarui."
        ]);
    }
}
