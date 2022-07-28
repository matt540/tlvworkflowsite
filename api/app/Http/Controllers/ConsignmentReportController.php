<?php

namespace App\Http\Controllers;

use App\Exports\ConsignmentReportExport;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repository\ProductsQuotationRepository as product_quotation_repo;
use Illuminate\Http\Resources\Json\JsonResource;
use Log, Excel;

class ConsignmentReportController extends Controller
{

    public function __construct(product_quotation_repo $product_quotation_repo)
    {
        $this->product_quotation_repo = $product_quotation_repo;
    }


    public function getAllConsignmentReport(Request $request)
    {

        ini_set('max_execution_time', 300000);

        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $product_data = $this->product_quotation_repo->getConsignmentReport($filter);

//        $data['recordsFiltered'] = $this->product_quotation_repo->getConsignmentReportTotal($filter);

        $data['data'] = $product_data['data'];
        $data['recordsTotal'] = $product_data['total'];
        $data['recordsFiltered'] = $product_data['recordsFiltered'];
        return response()->json($data, 200);
    }

    public function ConsignmentReportExport(Request $request)
    {
        ini_set('max_execution_time', 300000);
        try {
            $filter = $request->all();

            $product_data = $this->product_quotation_repo->getConsignmentReportExport();

            $file_name = 'consignment_Report_' . time() . '.xlsx';
            $file = 'public/exports/' . $file_name;

            $export = new ConsignmentReportExport($product_data['data']);

            if (ob_get_contents()) ob_end_clean();
            ob_start();

            Excel::store($export, $file);

            $path = asset('api/storage/exports/' . $file_name);
            return $path;

        } catch (\Exception $e) {
            Log::info('Error : ' . $e->getMessage());
            return response()->json('Something Went Wrong.', 400);
        }
    }


}
