<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SyncProductExport implements FromView
{
    private $products;

    public function __construct($products){
        $this->products = $products;
    }


    public function view(): View
    {
        return view('exports.syncProductExport', ['products' => $this->products]);
    }
}
