<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Role;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        $roles = ["Admin", "Kasir", "Gudang"];

        foreach($categories as $category) {
            Category::create([ "name" => $category ]);
        }

        foreach($transactionStatuses as $transactionStatus) {
            TransactionStatus::create([ "name" => $transactionStatus ]);
        }

        foreach($transactionTypes as $transactionType) {
            TransactionType::create([ "name" => $transactionType ]);
        }

        foreach($roles as $role) {
            Role::create([ "name" => $role ]);
        }

        $adminRole = Role::whereName("Admin")->first();

        User::create([
            "email" => "admin@gmail.com",
            "password" => Hash::make("admin"),
            "name" => "admin",
            "role_id" => $adminRole->id
        ]);
    }
}
