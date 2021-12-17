<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Repository\UserRepository as user_repo;
use App\Repository\EmailSendRecordRepository as email_send_record_repo;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use DateTime;

class EmailSendRecordController extends Controller
{

    public function __construct(email_send_record_repo $email_send_record_repo, user_repo $user_repo)
    {
        $this->user_repo = $user_repo;
        $this->email_send_record_repo = $email_send_record_repo;
    }


    public function getEmailSendRecordOfId(Request $request)
    {
        return $this->email_send_record_repo->getEmailSendRecordById($request->id);
    }

    public function getEmailSendRecords(Request $request)
    {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $category_data_total = $this->email_send_record_repo->getEmailSendRecords($filter);
        $data['data'] = $category_data_total['data'];

        $data['recordsTotal'] = $category_data_total['total'];
        $data['recordsFiltered'] = $this->email_send_record_repo->getEmailSendRecordsTotal($filter);
        return response()->json($data, 200);
    }

}
