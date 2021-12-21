<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;

class StorageProductsExport implements FromArray
{
    
    protected $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function headings(): array
    {
        return [
            'Seller Name',
            'Product Name',
            'SKU',
            'Storage Cost',
            'Storage Start Date'
        ];
    }

    public function array(): array
    {
        return $this->products;
    }

    // public function array(): array
    // {
    //     return [
    //         [1, 2, 3],
    //         [4, 5, 6]
    //     ];
    // }
}
