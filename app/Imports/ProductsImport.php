<?php

namespace App\Imports;

use App\Helpers\ExceptionResponseHelper;
use App\Models\Product;
use App\Models\ProductUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $unit = ProductUnit::whereName($row["unit"])->first();

        if(!$unit) {
            ExceptionResponseHelper::throwNotFoundError("Unit [" . $row["unit"] . "] tidak ditemukan.");
        }

        return new Product([
            "name" => $row["name"],
            "quantity" => $row["quantity"],
            "purchase_price" => $row["purchase_price"],
            "selling_price" => $row["selling_price"],
            "category" => $row["category"],
            "unit_id" => $unit->id
        ]);
    }
}
