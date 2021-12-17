<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Repository\UserRepository as user_repo;
use App\Repository\CategoryRepository as category_repo;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use DateTime;

class CategoryController extends Controller
{

    public function __construct(category_repo $category_repo, user_repo $user_repo)
    {
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
    }

    public function deleteCategory(Request $request)
    {
        $category = $this->category_repo->CategoryOfId($request->id);
        $this->category_repo->delete($category);
    }

    public function saveCategory(Request $request)
    {
        $data = $request->all();

        if ($request->id)
        {
            $details = $this->category_repo->CategoryOfId($request->id);
            $this->category_repo->update($details, $data);
        }
        else
        {
            $prepared_data = $this->category_repo->prepareData($data);

            if ($this->category_repo->create($prepared_data))
            {
                return response()->json('Created Successfully', 200);
            }
        }
    }

    public function getCategory(Request $request)
    {
        return $this->category_repo->getCategoryById($request->id);
    }

    public function getCategorys(Request $request)
    {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $category_data_total = $this->category_repo->getCategorys($filter);
        $data['data'] = $category_data_total['data'];

        $data['recordsTotal'] = $category_data_total['total'];
        $data['recordsFiltered'] = $this->category_repo->getCategorysTotal($filter);
        return response()->json($data, 200);
    }

    public function getAllCategorys()
    {
        $result = $this->category_repo->getAllCategorys();
//        unset($result[2]);
        unset($result[7]);
        $result=array_values($result);

//        foreach ($result as $key => $value)
//        {
//            $result[$key]['from_time'] = $value['from_time']->format('H:i:s');
//            $result[$key]['to_time'] = $value['to_time']->format('H:i:s');
//        }
        return $result;
    }
    
    public function changeCategoryStatus(Request $request)
    {
        $data = $request->all();

        $id = $data['category'];
        $data1['status'] = $data['status'];

        $details = $this->category_repo->CategoryOfId($id);

        $this->category_repo->update($details, $data1);
    }

}
