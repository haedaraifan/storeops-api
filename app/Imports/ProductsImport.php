<?php

namespace App\Imports;

use App\Helpers\ExceptionResponseHelper;
use App\Models\Product;
use App\Models\ProductUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    private $importedProducts = [];

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

        $product = new Product([
            "name" => $row["name"],
            "quantity" => $row["quantity"],
            "purchase_price" => $row["purchase_price"],
            "selling_price" => $row["selling_price"],
            "category" => $row["category"],
            "unit_id" => $unit->id
        ]);
        $product->save();

        $this->importedProducts[] = $product;
        return $product;
    }

    /**
    * Get the imported products.
    *
    * @return array
    */
    public function getImportedProducts()
    {
        return $this->importedProducts;
    }
}
