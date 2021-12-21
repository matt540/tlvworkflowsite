<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ProductWordProposalExport implements FromCollection
{
    // public function __construct($results, $member, $date)
    // {
    //     $this->date = $date;
    //     $this->member = $member;
    //     $this->results = $results;
    // }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

    }
}
