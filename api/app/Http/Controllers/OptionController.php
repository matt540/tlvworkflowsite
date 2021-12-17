<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Option_master;
use App\Repository\OptionRepository as option_repo;
use App\Repository\SelectRepository as select_repo;
use App\Repository\SellerRepository as seller_repo;
use Log;

class OptionController extends Controller
{

    public function __construct(select_repo $select_repo, option_repo $option_repo, seller_repo $seller_repo)
    {
        $this->option_repo = $option_repo;
        $this->select_repo = $select_repo;
        $this->seller_repo = $seller_repo;
    }

    public function setEmails(Request $request)
    {
        foreach ($request->all() as $key => $value)
        {
            $data['value_text'] = $value['value_text'];
            $option = $this->option_repo->OptionOfId($value['id']);
            $this->option_repo->update($option, $data);
        }
    }

    public function updateWPCatDetails(Request $request)
    {
        $data = $request->all();
        Log::info(json_encode($data));
        die;
        $data = $request->all();
    }

    public function createWPCatDetails(Request $request)
    {

        $data = $request->all();
        Log::info(json_encode($data));
        die;
        $option = [];
        $select_id = '';
        if ($data['taxonomy'] == "product_cat")
        {
            //Brand
            $select_id = 1;
        }
        else if ($data['taxonomy'] == "product_cat")
        {
            //cat
            $select_id = 2;
        }
        else if ($data['taxonomy'] == "product_cat")
        {
            //Collection
            $select_id = 3;
        }
        else if ($data['taxonomy'] == "product_cat")
        {
            //Room
            $select_id = 4;
        }
        else if ($data['taxonomy'] == "product_cat")
        {
            //Look
            $select_id = 5;
        }
        else if ($data['taxonomy'] == "product_cat")
        {
            //Color
            $select_id = 6;
        }
        else if ($data['taxonomy'] == "product_cat")
        {
            //Condition
            $select_id = 7;
        }
        if ($seller_id != '')
        {
            $option['select_id'] = $this->select_repo->SelectOfId($select_id);
            $option['key_text'] = $data['name'];
            $option['value_text'] = $data['name'];
            $option['wp_id'] = $data['term_id'];
            $this->option_repo->create($option);
        }
    }

    public function deleteWPCatDetails(Request $request)
    {
        $data = $request->all();

        Log::info(json_encode($data));
        die;
        $option = $this->option_repo->OptionOfId($data['id']);
        $this->option_repo->delete($option);
    }

    public function getPickUpLocationsBySelectIdSellerId($select_id, $seller_id)
    {
        $data = $this->option_repo->get_all_of_select_id_seller_id($select_id, $seller_id);
        foreach ($data as $key => $value)
        {
            $data[$key]['key_text'] = json_decode($value['key_text']);
        }
        return $data;
    }

    public function getOptionsBySelectId($select_id)
    {
        return $this->option_repo->get_all_of_select_id($select_id);
    }

    public function getActiveOptionsBySelectId($select_id)
    {
        return $this->option_repo->get_all_active_of_select_id($select_id);
    }

    public function getAllOptionsBySelectId(Request $request)
    {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $users_data_total = $this->option_repo->getOptions($filter);
        $data['data'] = $users_data_total['data'];

        $data['recordsTotal'] = $users_data_total['total'];
        $data['recordsFiltered'] = $this->option_repo->getOptionsTotal($filter);
        return response()->json($data, 200);
    }

    public function getOptionById($id)
    {
        return $this->option_repo->OptionById($id);
    }

    public function saveOption(Request $request)
    {
        $data = $request->all();

        if ($data['select_id'] == 6)
        {
            $data['key_text'] = json_encode($data['key_text']);
        }
        $data['select_id'] = $this->select_repo->SelectOfId($data['select_id']);

        if ($request->id)
        {
            $option = $this->option_repo->OptionOfId($request->id);
            $option_obj = $this->option_repo->update($option, $data);
        }
        else
        {
            if (isset($data['seller_id']))
            {
                $data['seller_id'] = $this->seller_repo->SellerOfId($data['seller_id']);
            }
            $option = $this->option_repo->prepareData($data);
            $option_obj = $this->option_repo->create($option);
        }
        $option = $this->getOptionById($option_obj->getId());
        if ($data['select_id']->getId() == 6)
        {
             $option['key_text'] = json_decode($option['key_text']);
    }
        return $option;
    }

}
