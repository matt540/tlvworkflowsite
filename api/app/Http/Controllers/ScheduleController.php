<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Requests;

use App\Entities\Users;

use App\Entities\Role;

use App\Repository\UserRepository as user_repo;

use App\Repository\ProductsApprovedRepository as product_approved_repo;

use App\Repository\ScheduleRepository as schedule_repo;

use App\Repository\ProductsQuotationRepository as product_quotation_repo;

use Auth;

use Tymon\JWTAuth\Facades\JWTAuth;



class ScheduleController extends Controller

{



    public function __construct(product_quotation_repo $product_quotation_repo, schedule_repo $schedule_repo, product_approved_repo $product_approved_repo, user_repo $user_repo)

    {

        $this->user_repo = $user_repo;

        $this->product_approved_repo = $product_approved_repo;

        $this->schedule_repo = $schedule_repo;

        $this->product_quotation_repo = $product_quotation_repo;

    }



    public function deleteScheduleTemp(Request $request)

    {

        $data = $request->all();

        $user = $this->schedule_repo->ScheduleOfId($request->id);

        $this->schedule_repo->delete($user);

    }



    public function deleteSchedule(Request $request)

    {

        $data = $request->all();

        $data3 = array();



        $details = $this->schedule_repo->getSellerAllSchedule($data);



//        if ($this->schedule_repo->deleteSellerAllSchedule($data))

//        {

        $data3['is_scheduled'] = 0;

        foreach ($details as $key => $value)

        {

            $temp = $this->schedule_repo->ScheduleOfId($value['id']);

            $this->schedule_repo->delete($temp);



            $data2 = $this->product_quotation_repo->ProductQuotationOfId($value['product_quote_id']);

            $this->product_quotation_repo->update($data2, $data3);

        }

//        }

    }



    public function getSchedule(Request $request)

    {

        $data = $this->schedule_repo->getScheduleById($request->all());

        $data['date'] = $data['date']->format('Y-m-d');

        $data['time'] = $data['time']->format('Y-m-d H:i:s');

        return $data;

    }



    public function getScheduleByProductQuotationId(Request $request)

    {

        $data = $this->schedule_repo->getScheduleByProductQuotId($request->product_quotation_id);



        if (!empty($data))

        {

            $data[0]['date'] = $data[0]['date']->format('Y-m-d');

            $data[0]['time'] = $data[0]['time']->format('Y-m-d H:i:s');

        }

        return $data;

    }



    public function getScheduleByProductId(Request $request)

    {

        $data = $this->schedule_repo->getScheduleByProductId($request->product_id);



        if (!empty($data))

        {

            $data[0]['date'] = $data[0]['date']->format('Y-m-d');

            $data[0]['time'] = $data[0]['time']->format('Y-m-d H:i:s');

        }

        return $data;

    }



    public function getSchedules(Request $request)

    {

        $filter = $request->all();



        $data['draw'] = $filter['draw'];



        $users_data_total = $this->schedule_repo->getSchedules($filter);

        $data['data'] = $users_data_total['data'];



        foreach ($data['data'] as $key => $value)

        {

            $data['data'][$key]['schedule_date'] = $value['schedule_date']->format('Y-m-d');

            $data['data'][$key]['schedule_time'] = $value['schedule_time']->format('H:i:s');

        }



        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->schedule_repo->getSchedulesTotal($filter);



        return response()->json($data, 200);

    }



    public function getSellerIdOfProductQuot(Request $request)

    {

        $data = $request->all();

        $data['product_quot_id'] = $this->product_quotation_repo->ProductQuotationOfId($data['product_quot_id']);



        $seller_id = $data['product_quot_id']->getProductId()->getSellerid()->getId();



        return $seller_id;

    }



    public function saveAllScheduleOfSeller(Request $request)

    {



        $data = $request->all();

        $data2['date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($data['date'])));

        $data2['time'] = new \DateTime(date('H:i:s', strtotime($data['time'])));

        $data['product_quot_id'] = $this->product_quotation_repo->ProductQuotationOfId($data['product_quot_id']);

        $seller_id = $data['product_quot_id']->getProductId()->getSellerid()->getId();

        $quots_of_seller = $this->product_quotation_repo->getAllProductQuotationsOfSeller($seller_id);

        if (count($quots_of_seller) > 0)

        {



            foreach ($quots_of_seller as $key => $value)

            {

                $schedule = '';

                $schedule = $this->schedule_repo->ScheduleOfProductQuotId($value['product_quot_id']);

                if (empty($schedule))

                {

                    $data2['product_quot_id'] = '';

                    $data2['product_quot_id'] = $this->product_quotation_repo->ProductQuotationOfId($value['product_quot_id']);

                    $data2['seller_id'] = $data2['product_quot_id']->getProductId()->getSellerid()->getWp_seller_id();



//                    $host = 'https://localvault.staging.wpengine.com/wp-content/themes/localvault/tlvotherinfo.php?skey=tlvesbyat&user_id=' . $data2['seller_id'];
                    $host = env('WP_URL').'/wp-content/themes/localvault/tlvotherinfo.php?skey=tlvesbyat&user_id=' . $data2['seller_id'];

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $host);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                    curl_setopt($ch, CURLOPT_HEADER, false);

                    //temp_stop

                    $temp = curl_exec($ch);

                    $temp = json_decode($temp, true);



                    if ($temp['fname'] != '' && $temp['lname'] != '')

                    {

                        $data2['seller_name'] = $temp['fname'] . ' ' . $temp['lname'];

                    }

                    else

                    {

                        $data2['seller_name'] = $temp['user_login'];

                    }



                    $prepared_data = $this->schedule_repo->prepareData($data2);

                    $scheduled = $this->schedule_repo->create($prepared_data);

                    if ($scheduled)

                    {

                        $data3['is_scheduled'] = 1;

                        $this->product_quotation_repo->update($data2['product_quot_id'], $data3);

                    }

                }

            }

        }

        return response()->json('Product Scheduled Successfully', 200);

    }



    public function saveSchedule(Request $request)

    {

        $data = $request->all();



        $data_temp = array();

        $data_temp['id'] = $data['seller_id'];

        $data_temp['date'] = $data['olddate'];

        $data_temp['time'] = $data['oldtime'];



        $data['product_quot_id'] = $this->product_quotation_repo->ProductQuotationOfId($data['product_quot_id']);

        $data['date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($data['date'])));

        $data['time'] = new \DateTime(date('H:i:s', strtotime($data['time'])));



        if ($request->id)

        {

            $details = $this->schedule_repo->getSellerAllSchedule($data_temp);



            foreach ($details as $key => $value)

            {

                $details = $this->schedule_repo->ScheduleOfId($value['id']);

                $this->schedule_repo->update($details, $data);

            }

            return response()->json('Schedule Updated Successfully', 200);

//            $details = $this->schedule_repo->ScheduleOfId($request->id);

//            if ($this->schedule_repo->update($details, $data))

//            {

//                return response()->json('Schedule Updated Successfully', 200);

//            }

        }

        else

        {

            $prepared_data = $this->schedule_repo->prepareData($data);

            $scheduled = $this->schedule_repo->create($prepared_data);

//            if ($scheduled)

//            {

//                $data2['is_scheduled'] = 1;

//                $this->product_approved_repo->update($data['product_id'], $data2);

//            }

            if ($scheduled)

            {

                $data2['is_scheduled'] = 1;

                $this->product_quotation_repo->update($data['product_quot_id'], $data2);

            }

            return response()->json('Product saved Successfully', 200);

        }

    }



}

