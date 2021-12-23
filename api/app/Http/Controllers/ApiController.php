<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Requests;

use App\Entities\Users;

use App\Entities\Role;

use App\Repository\UserRepository as user_repo;

use App\Repository\RoleRepository as role_repo;

use App\Repository\OptionRepository as option_repo;

use App\Repository\ProductsRepository as product_repo;

use App\Repository\SellRepository as sell_repo;

use App\Repository\SubCategoryRepository as sub_category_repo;

use App\Repository\EmailTemplateRepository as email_template_repo;

use App\Repository\ProductsApprovedRepository as product_approved_repo;

use App\Repository\ProductsQuotationRepository as product_quote_repo;

use App\Repository\ImagesRepository as image_repo;

use App\Repository\CategoryRepository as cat_repo;

use App\Repository\MailRecordRepository as mail_record_repo;

use Auth;

use Google_Service_Drive;

use Tymon\JWTAuth\Facades\JWTAuth;



class ApiController extends Controller

{



    public function __construct(product_quote_repo $product_quote_repo,Google_Service_Drive $Google_Service_Drive, mail_record_repo $mail_record_repo, cat_repo $cat_repo, image_repo $image_repo, product_approved_repo $product_approved_repo, email_template_repo $email_template_repo, option_repo $option_repo, sub_category_repo $sub_category_repo, sell_repo $sell_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo)

    {

        $this->Google_Service_Drive = $Google_Service_Drive;

        $this->product_repo = $product_repo;

        $this->product_quote_repo = $product_quote_repo;

        $this->mail_record_repo = $mail_record_repo;

        $this->user_repo = $user_repo;

        $this->role_repo = $role_repo;

        $this->sell_repo = $sell_repo;

        $this->option_repo = $option_repo;

        $this->sub_category_repo = $sub_category_repo;

        $this->email_template_repo = $email_template_repo;

        $this->product_approved_repo = $product_approved_repo;

        $this->image_repo = $image_repo;

        $this->cat_repo = $cat_repo;

    }



    public function getAllSyncProductsOfSellerHome()

    {

       $data= $this->product_quote_repo->getAllSyncProductsOfSellerHome();





       echo json_encode($data);



    }



    public function redirectToLatestGoogleDoc($basename)

    {

        $MailRecord = $this->mail_record_repo->getMailRecordByBaseName($basename);



        if ($MailRecord != NULL)

        {



            $dir = '/';

            $recursive = false; // Get subdirectories also?

            $files = collect(\Storage::disk('google')->listContents($dir, $recursive))

                    ->where('type', '=', 'file')

                    ->where('filename', '=', pathinfo($MailRecord->getFileName(), PATHINFO_FILENAME))

                    ->where('extension', '=', pathinfo($MailRecord->getFileName(), PATHINFO_EXTENSION))

                    ->sortBy('timestamp');

//                    ->last();







            if (count($files) > 1)

            {

                \Storage::disk('google')->getAdapter()->delete($basename);

            }

            $file1 = collect(\Storage::disk('google')->listContents($dir, $recursive))

                    ->where('type', '=', 'file')

                    ->where('filename', '=', pathinfo($MailRecord->getFileName(), PATHINFO_FILENAME))

                    ->where('extension', '=', pathinfo($MailRecord->getFileName(), PATHINFO_EXTENSION))

                    ->sortBy('timestamp')

                    ->last();









            if ($file1 != '')

            {

                return redirect('https://drive.google.com/file/d/' . $file1['path']);

            }

            else

            {

                return redirect(config('app.url'));

            }

        }

        else

        {

            return redirect(config('app.url'));

        }

    }



    public function apiCall(Request $request)

    {

        $data = $request->getContent();

        $data = json_decode($data, true);



        if ($data['key'] == 'api-1489153922595127306')

        {

            return 'api running successfully';

        }

    }



    public function saveBrands(Request $request)

    {

//        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/cat-api.php?cat=product_brand';

//        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/cat-api.php?cat=product_cat';

//        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/cat-api.php?cat=product_coll_tax';

//        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/cat-api.php?cat=product_room_tax';

//        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/cat-api.php?cat=product_look_tax';

//        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/cat-api.php?cat=product_color';

        $host = env('WP_URL').'/wp-content/themes/thelocalvault/cat-api.php?cat=product_condition';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HEADER, false);

        //temp_stop

        $temp = curl_exec($ch);



        $temp = json_decode($temp, true);

        echo "<pre>";

        print_r($temp);

        die;

        foreach ($temp as $key => $value)

        {



            $cat = $this->sub_category_repo->SubCategoryOfWpId($value['term_id']);

            if ($cat)

            {



            }

            else

            {

//                echo "<pre>";

//                print_r($value);

//                die;

                $temp_data = [];

                $temp_data['wp_term_id'] = $value['term_id'];

                $temp_data['sub_category_name'] = $value['name'];

                $temp_data['category_id'] = $this->cat_repo->CategoryOfId(7);

                $prepared_data = $this->sub_category_repo->prepareData($temp_data);

                $this->sub_category_repo->create($prepared_data);

            }

        }

        echo "<pre>";

        print_r('asdad');

        die;

        foreach ($temp as $key => $value)

        {

            $temp_data = [];

            $subcategory = $this->sub_category_repo->SubCategoryOfWpId($value['term_id']);

            if (isset($value['parent']) && $value['parent'] != 0)

            {

                if ($subcategory)

                {

                    $temp_data['parent_id'] = $this->sub_category_repo->SubCategoryOfWpId($value['parent']);

                    $this->sub_category_repo->update($subcategory, $temp_data);

                }

            }

        }

        echo "<pre>";

        print_r($temp);

        die;

        foreach ($temp as $key => $value)

        {





            $temp_data = [];

            $temp_data['wp_term_id'] = $value['term_id'];

            $temp_data['sub_category_name'] = $value['name'];

            $temp_data['category_id'] = $this->cat_repo->CategoryOfId(7);

            $prepared_data = $this->sub_category_repo->prepareData($temp_data);

            $this->sub_category_repo->create($prepared_data);

        }

        echo "<pre>";

        print_r('in');

        die;

    }



    public function saveRooms(Request $request)

    {

        $data = $request->all();

        echo "<pre>";

        print_r($data);

        die;

        foreach ($data as $key => $dataval)

        {

            $val = array();

            $val['sub_category_name'] = $dataval['name'];

            $val['wp_term_id'] = $dataval['term_id'];



            $val['category_id'] = $this->cat_repo->CategoryOfId(7);



            $room = $this->sub_category_repo->prepareData($val);

            $this->sub_category_repo->create($room);

        }

        return "success";

    }



    public function saveProductLookToMultiple()

    {

        echo "<pre>";

        print_r('in');

        die;

          ini_set('max_execution_time', 30000);

        $products = $this->product_repo->getAllProducts();

        $count = 0;

        foreach ($products as $key => $product)

        {



            if ($product['look'] != null)

            {

                $update_product = $this->product_repo->ProductOfId($product['id']);

                $data_product = [];

                $data_product['product_look'] = [];

                $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($product['look']['id']);

                $this->product_repo->update($update_product, $data_product);

                echo "<pre>";

                print_r($update_product->getId());



            }

        }

        echo "<pre>";

        print_r('Done');

        die;

    }



    public function saveProductCollectionToMultiple()

    {

        echo "<pre>";

        print_r('in');

        die;

        $products = $this->product_repo->getAllProducts();

        $count = 0;

        foreach ($products as $key => $product)

        {

            if ($product['collection'] != null)

            {

                $update_product = $this->product_repo->ProductOfId($product['id']);

                $data_product = [];

                $data_product['product_collection'] = [];

                $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($product['collection']['id']);

                $this->product_repo->update($update_product,$data_product);

            }

        }

        echo "<pre>";

        print_r('Done');

        die;

    }



}

