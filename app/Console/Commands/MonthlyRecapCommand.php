<?php

namespace App\Console\Commands;

use App\Models\AddProductHistory;
use App\Models\Product;
use App\Models\ProductsRecap;
use App\Models\RestockProductHistory;
use App\Models\TransactionProduct;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MonthlyRecapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recap:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handles monthly product recap at the start of each month.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastRecap = ProductsRecap::where("date", Carbon::now()->startOfMonth()->subMonth()->endOfMonth())
            ->exists();

        if($lastRecap) {
            $this->info('Recap has already been done for ' . Carbon::now()->format('F Y'));
            return;
        }

        $this->performRecap();

        $this->info('Monthly recap completed for ' . Carbon::now()->format('F Y'));
    }

    protected function performRecap()
    {
        $startDate = Carbon::now()->startOfMonth()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
        $products = Product::get();

        $incomingQuantity = RestockProductHistory::selectRaw("product_id AS id, name, SUM(quantity) as quantity")
            ->whereBetween("date", [$startDate, $endDate])
            ->groupBy("product_id", "name")
            ->get();

        $outgoingQuantity = TransactionProduct::selectRaw("product_id AS id, name, SUM(quantity) as quantity")
            ->whereHas("transaction", function ($query) use ($startDate, $endDate) {
                $query->whereBetween("date", [$startDate, $endDate]);
            })
            ->groupBy("product_id", "name")
            ->get();

        $firstQuantityFromRecap = ProductsRecap::selectRaw("product_id AS id, last_quantity")
            ->whereBetween("date", [$startDate, $endDate])
            ->get();

        foreach($products as $product) {
            $incoming = $incomingQuantity->firstWhere("id", $product->id);
            $outgoing = $outgoingQuantity->firstWhere("id", $product->id);
            $first = $firstQuantityFromRecap->firstWhere("id", $product->id)
                ?? AddProductHistory::selectRaw("product_id AS id, quantity")
                ->where("product_id", $product->id)
                ->first();

            ProductsRecap::create([
                "date" => $endDate,
                "product_id" => $product->id,
                "name" => $product->name,
                "category" => $product->category,
                "first_quantity" => $first->quantity,
                "last_quantity" => $product->quantity,
                "incoming_quantity" => $incoming->quantity ?? 0,
                "outgoing_quantity" => $outgoing->quantity ?? 0,
            ]);
        }
    }
}
