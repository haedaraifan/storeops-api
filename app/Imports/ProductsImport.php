<?php

namespace App\Imports;

use App\Helpers\ExceptionResponseHelper;
use App\Http\Requests\ProductImportRowRequest;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Support\Facades\Validator;
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
        $request = new ProductImportRowRequest();
        $request->merge($row);

        $validator = Validator::make($request->all(), $request->rules(), $request->messages());
        if($validator->fails()) {
            $errorMessages = $validator->errors()->first();
            ExceptionResponseHelper::throwInvariantError($errorMessages);
        }

        $unit = ProductUnit::whereName($row["unit"] ?? null)->first();

        $product = new Product([
            "name" => $row["name"],
            "quantity" => $row["quantity"],
            "purchase_price" => $row["purchase_price"],
            "selling_price" => $row["selling_price"],
            "category" => $row["category"],
            "unit_id" => $unit->id ?? null
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
