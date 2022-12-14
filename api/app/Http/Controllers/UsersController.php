<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Requests;

use App\Entities\Users;

use App\Entities\Role;

use App\Repository\UserRepository as user_repo;

use App\Repository\RoleRepository as role_repo;

use App\Repository\OptionRepository as option_repo;

use App\Repository\SellerRepository as seller_repo;

use Auth;

use Tymon\JWTAuth\Facades\JWTAuth;



class UsersController extends Controller

{



    public function __construct(seller_repo $seller_repo, option_repo $option_repo, user_repo $user_repo, role_repo $role_repo)

    {

        $this->option_repo = $option_repo;

        $this->user_repo = $user_repo;

        $this->role_repo = $role_repo;

        $this->seller_repo = $seller_repo;

    }



    public function deleteUser(Request $request)

    {

        $user = $this->user_repo->UserOfId($request->id);

        $this->user_repo->delete($user);

    }



    public function registerUser(Request $request)

    {

        $data = $request->all();



        $data['role'] = array($this->role_repo->RoleOfId($data['usertype']));

        $data['status'] = $this->option_repo->OptionOfId($data['status']);



        if ($request->password != '' && $request->password != '********')

        {

            $data['password'] = bcrypt($request->password);

        }

        else

        {

            unset($data['password']);

        }



        if ($request->id)

        {

            $user = $this->user_repo->UserOfId($request->id);

            $user->setRoles($data['role']);

            $this->user_repo->update($user, $data);

            return response()->json('Updated Successfully', 200);

        }

        else

        {

            $prepared_data = $this->user_repo->prepareData($data);



            if ($this->user_repo->create($prepared_data))

            {

                return response()->json('Registered Successfully', 200);

            }

        }

    }



    public function editAuthUser(Request $request)

    {

        $data = $request->all();

        unset($data['status']);

        $authuser = JWTAuth::parseToken()->authenticate();



//        $data['status'] = $this->option_repo->OptionOfId($data['status']);

//        if ($authuser->getRoles()[0]->getId() == 1)

//        {

//            if ($data['newpassword'] == $data['confirmpassword'] && $data['confirmpassword'] != '' && \Hash::check($data['oldpassword'], $authuser->getPassword()))

//            {

//                $data['password'] = bcrypt($request->newpassword);

//            }

//            else

//            {

//                unset($data['password']);

//            }

//        }

//        else

//        {



        if ($data['password'] == "********")

        {

            unset($data['password']);

        }

        else

        {

            $data['password'] = bcrypt($request->password);

        }

//        }

        $user = $this->user_repo->UserOfId($request->id);

        $this->user_repo->update($user, $data);

        return response()->json('Updated Successfully', 200);

    }



    public function getUser(Request $request)

    {

        return $this->user_repo->getUserById($request->id);

    }



    public function getAllUsers()

    {

        return $this->user_repo->getAllUsers();

    }



    public function getAllCompany()

    {

        return $this->company_repo->getAllCompany();

    }



    public function getUsers(Request $request)

    {

        $filter = $request->all();



        $data['draw'] = $filter['draw'];



        $users_data_total = $this->user_repo->getUsers($filter);

        $data['data'] = $users_data_total['data'];



        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->user_repo->getUsersTotal($filter);

        return response()->json($data, 200);

    }



    public function getUserPaymentOption()

    {

        $data = $this->option_repo->get_all_of_select_id(6);

        return $data;

    }



    public function changeUserStatus(Request $request)

    {

        $data = $request->all();



//        $user_id = decrypt($data['user']);

        $user_id = $data['user'];

        $data1['status'] = $this->option_repo->OptionOfId($data['status']);



        $user = $this->user_repo->UserOfId($user_id);



        $this->user_repo->update($user, $data1);

    }



    public function getAllStatus()

    {

        $data = $this->option_repo->get_all_of_select_id(2);

        return $data;

    }



    public function wpapi()

    {



    }



    public function IsShopUrlAvailable(Request $request)

    {

        $dd = $request->all();

//        $d['key'] = 'mltvqwqs';

        $d['userurl'] = $dd['shop_url'];





        $data = json_encode($d);





        $data = array('name' => 'Ross', 'php_master' => true);

//        $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-user.php?userurl=' . $dd['shop_url'];
        $host = env('WP_URL').'/wp-content/themes/thelocalvault/new-user.php?userurl=' . $dd['shop_url'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);



        $temp = curl_exec($ch);



        return $temp;

    }



    public function IsSellerEmailAvailable(Request $request)

    {

        $dd = $request->all();

        $d['key'] = 'mltvqwqs';

        $d['useremail'] = $dd['useremail'];





        $data['data'] = json_encode($d);





        $data = array('name' => 'Ross', 'php_master' => true);

//        $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-user.php?useremail=' . $dd['useremail'];
        $host = env('WP_URL').'/wp-content/themes/thelocalvault/new-user.php?useremail=' . $dd['useremail'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);



        $temp = curl_exec($ch);



        return $temp;

    }



    public function saveSeller(Request $request)

    {

        $d = $request->all();

        $seller = $d;

        $d['key'] = 'mltvqwqs';



        $data['data'] = json_encode($d);



//        $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-user.php';
        $host = env('WP_URL').'/wp-content/themes/thelocalvault/new-user.php';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //temp_stop

        $temp = curl_exec($ch);

        $temp2 = json_decode($temp);

        $seller['shopname'] = $d['shop_name'];

        $seller['shopurl'] = $d['shop_url'];

        $seller['password'] = bcrypt($d['password']);

        $seller['wp_seller_id'] = $temp2->data->ID;

        $seller['displayname'] = $temp2->data->display_name;



        $prepareData = $this->seller_repo->prepareData($seller);

        $this->seller_repo->create($prepareData);



        return $temp;



//        return '({"data":{"ID":"818","user_login":"matt12345","user_pass":"$P$Bsc.QDe9dqErc032wTSXt33BLIb2sz0","user_nicename":"matt12345","user_email":"matt12345@540designstudio.com","user_url":"","user_registered":"2017-05-09 12:50:00","user_activation_key":"","user_status":"0","display_name":"11 11"},"ID":818,"caps":{"seller":true},"cap_key":"wp_capabilities","roles":["seller"],"allcaps":{"read":true,"publish_posts":true,"manage_categories":true,"moderate_comments":true,"unfiltered_html":true,"upload_files":true,"dokandar":true,"seller":true},"filter":null})';

    }



    public function getAllCopywriters(Request $request)

    {

        //5 for Copywriter

        return $this->user_repo->getAllCopywriters();

    }

    public function getAllCopywritersAndAdmins(Request $request)

    {

        //5 for Copywriter

        return $this->user_repo->getAllCopywritersAndAdmins();

    }


    public function getAllAgents(){

        return $this->user_repo->getAllAgents();
    }

}

