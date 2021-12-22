<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToArray, WithHeadingRow
{
    use Importable;
    public $data;
    public function array($array)
    {
        $this->data = $array;

    }
}
