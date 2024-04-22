<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = ["Pcs", "Kg"];
        $transactionStatuses = ["Lunas", "Belum lunas"];
        $transactionTypes = ["Penjualan", "Pengeluaran"];

        foreach($categories as $category) {
            Category::create([ "name" => $category ]);
        }

        foreach($transactionStatuses as $transactionStatus) {
            TransactionStatus::create([ "name" => $transactionStatus ]);
        }

        foreach($transactionTypes as $transactionType) {
            TransactionType::create([ "name"=> $transactionType ]);
        }
    }
}
