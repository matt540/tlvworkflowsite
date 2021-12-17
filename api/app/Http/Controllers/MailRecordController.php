<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Repository\UserRepository as user_repo;
use App\Repository\MailRecordRepository as mail_record_repo;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use DateTime;

class MailRecordController extends Controller
{

    public function __construct(mail_record_repo $mail_record_repo, user_repo $user_repo)
    {
        $this->user_repo = $user_repo;
        $this->mail_record_repo = $mail_record_repo;
    }

//    public function deleteMailRecord(Request $request)
//    {
//        $category = $this->mail_record_repo->MailRecordOfId($request->id);
//        $this->mail_record_repo->delete($category);
//    }

    public function saveMailRecord(Request $request)
    {
        $data = $request->all();

        if ($request->id)
        {
            $details = $this->mail_record_repo->MailRecordOfId($request->id);
            $this->mail_record_repo->update($details, $data);
        }
        else
        {
            $prepared_data = $this->mail_record_repo->prepareData($data);

            if ($this->mail_record_repo->create($prepared_data))
            {
                return response()->json('Created Successfully', 200);
            }
        }
    }

    public function getMailRecord(Request $request)
    {
        return $this->mail_record_repo->getMailRecordById($request->id);
    }

    public function getMailRecords(Request $request)
    {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $category_data_total = $this->mail_record_repo->getMailRecords($filter);
        $data['data'] = $category_data_total['data'];

        $data['recordsTotal'] = $category_data_total['total'];
        $data['recordsFiltered'] = $this->mail_record_repo->getMailRecordsTotal($filter);
        return response()->json($data, 200);
    }

    public function getAllMailRecords()
    {
        $result = $this->mail_record_repo->getAllMailRecords();
//        unset($result[2]);
//        unset($result[4]);
        $result=array_values($result);

//        foreach ($result as $key => $value)
//        {
//            $result[$key]['from_time'] = $value['from_time']->format('H:i:s');
//            $result[$key]['to_time'] = $value['to_time']->format('H:i:s');
//        }
        return $result;
    }
    
}
