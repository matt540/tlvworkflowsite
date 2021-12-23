<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use File;
use \Image;
use App\Repository\ImagesRepository as image_repo;
use App\Repository\ProductsApprovedRepository as product_approved_repo;
use App\Repository\ProductsRepository as product_repo;

class UploadController extends Controller {

    public function __construct(product_repo $product_repo, image_repo $image_repo, product_approved_repo $product_approved_repo) {
          ini_set('memory_limit', '4096M');
        $this->image_repo = $image_repo;
        $this->product_repo = $product_repo;
        $this->product_approved_repo = $product_approved_repo;
    }

    ### upload user avatar & news picture

    public function uploadImages(Request $request) {
        $file = $request->file('photo');
        $size = File::size($file);
        $extension = $file->getClientOriginalExtension();

        $image_original = Image::make($file->getRealPath());
        if ($extension != 'png') {
            $image = imagecreatefromstring(file_get_contents($_FILES["photo"]["tmp_name"]));
            $exif = @exif_read_data($_FILES["photo"]["tmp_name"]);



            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 8:
                        $image_original->rotate(90);
// $image = imagerotate($image, 90, 0);
                        break;
                    case 3:
                        $image_original->rotate(180);
// $image = imagerotate($image, 180, 0);
                        break;
                    case 6:
                        $image_original->rotate(-90);
// $image = imagerotate($image, -90, 0);
                        break;
                }
            }
        }

        $destinationPath = public_path() . '/../../Uploads/' . $request['folder'] . '/';
        @mkdir(public_path() . '/../../Uploads/' . $request['folder'], 0777);


        $filename = str_random(25) . '.' . $extension;
        $allowed = array('gif', 'png', 'jpg', 'Jpeg', 'jpeg', 'JPG', 'PNG', 'GIF');

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

//        $upload_success = $request->file('photo')->move($destinationPath, $filename);
        $upload_success = $image_original->save($destinationPath . $filename);
        
        if ($upload_success && in_array($ext, $allowed)) {
            if ($ext == 'pdf') {
                
            } else {
                @mkdir($destinationPath . 'thumb', 0777);
                $img = Image::make($destinationPath . $filename);
                $img->resize(150, 150);

                $img->save($destinationPath . 'thumb/' . $filename);

                return response()->json(['filename' => $filename, 'size' => $size]);

                @mkdir($destinationPath . 'icon', 0777);
                $img = Image::make($destinationPath . $filename);
                $img->resize(40, 40);

                $img->save($destinationPath . 'icon/' . $filename);
            }
            return response()->json(['filename' => $filename, 'size' => $size]);
        } else if ($upload_success) {
            return response()->json(['filename' => $filename, 'size' => $size]);
        } else {
            return 'YEP: Problem in file upload';
        }
    }

    public function deleteUpload($folder, $image) {
        $filename = $image;
        $path_final_dir = public_path() . '/../../Uploads/' . $folder . '/';

        $thumb_path = 'thumb/';

        if (File::delete($path_final_dir . $filename)) {
            if (File::delete($path_final_dir . $thumb_path . $filename)) {
                
            }

            return 1;
        } else {
            return 0;
        }
    }

    public function uploadProductImages(Request $request) {

//     ini_set('upload_max_filesize', '20M');

        $file = $request->file('photo');

        $size = File::size($file);
        $extension = $file->getClientOriginalExtension();

        $image_original = Image::make($file->getRealPath());
        if ($extension != 'png') {
            $image = imagecreatefromstring(file_get_contents($_FILES["photo"]["tmp_name"]));
            $exif = @exif_read_data($_FILES["photo"]["tmp_name"]);
            \Log::info($exif);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 8:
                        $image_original->rotate(90);
// $image = imagerotate($image, 90, 0);
                        break;
                    case 3:
                        $image_original->rotate(180);
// $image = imagerotate($image, 180, 0);
                        break;
                    case 6:
                        $image_original->rotate(-90);
// $image = imagerotate($image, -90, 0);
                        break;
                }
            }
            
        }
        $destinationPath = public_path() . '/../../Uploads/' . $request['folder'] . '/';
        @mkdir(public_path() . '/../../Uploads/' . $request['folder'], 0777);
        $filename = str_random(25) . '.' . $extension;
        $allowed = array('gif', 'png', 'jpg', 'Jpeg', 'jpeg', 'JPG', 'PNG', 'GIF');

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
 \Log::info('Ext:'.$ext);
//        $upload_success = $request->file('photo')->move($destinationPath, $filename);

        $upload_success = $image_original->save($destinationPath . $filename);

       
        if ($upload_success && in_array($ext, $allowed)) {
            if ($ext == 'pdf') {
                
            } else {
                
                 \Log::info('filename:'.$filename);
                @mkdir($destinationPath . 'thumb', 0777);
                  
                $img = Image::make($destinationPath . $filename);
//                $img = Image::make($file->getRealPath());
                $img->resize(150, 150);

               $st= $img->save($destinationPath . 'thumb/' . $filename);
                \Log::info('sddd:'.$st);
             
                $imageData = array();
                $imageData['name'] = $filename;
                $preparedData = $this->image_repo->prepareData($imageData);
                $imageid = $this->image_repo->create($preparedData);

                 \Log::info('imageId:'.$imageid);
                return response()->json(['filename' => $filename, 'id' => $imageid, 'size' => $size]);

                @mkdir($destinationPath . 'icon', 0777);
                $img = Image::make($destinationPath . $filename);
                $img->resize(40, 40);

                $img->save($destinationPath . 'icon/' . $filename);
            }
            return response()->json(['filename' => $filename, 'id' => 0, 'size' => $size]);
        } else if ($upload_success) {
            return response()->json(['filename' => $filename, 'id' => 0, 'size' => $size]);
        } else {
            return 'YEP: Problem in file upload';
        }
    }


    public function deleteProductUpload(Request $request) {
        $details = $request->all();

        $filename = $details['name'];
        $path_final_dir = public_path() . '/../../Uploads/' . $details['folder'] . '/';

        $thumb_path = 'thumb/';

        if (File::delete($path_final_dir . $filename)) {
            if (File::delete($path_final_dir . $thumb_path . $filename)) {
                
            }

            foreach ($details['imgs'] as $key_item => $value_item) {
                $data_product['product_images'][] = $this->image_repo->ImageOfId($value_item);
            }

            $details_temp = $this->product_approved_repo->ProductApprovedOfId($details['product_id']);
            $this->product_approved_repo->update($details_temp, $data_product);

            $image = $this->image_repo->ImageOfId($details['id']);
            $this->image_repo->delete($image);

            return 1;
        } else {
            return 0;
        }
    }

    public function deleteProductUploadPending(Request $request) {
        $details = $request->all();

        $filename = $details['name'];
        $path_final_dir = public_path() . '/../../Uploads/' . $details['folder'] . '/';

        $thumb_path = 'thumb/';

        if (File::delete($path_final_dir . $filename)) {
            if (File::delete($path_final_dir . $thumb_path . $filename)) {
                
            }
            $data_product['product_pending_images'] = array();
            foreach ($details['imgs'] as $key_item => $value_item) {
                $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value_item);
            }

            $details_temp = $this->product_repo->ProductOfId($details['product_id']);
            $this->product_repo->update($details_temp, $data_product);

            $image = $this->image_repo->ImageOfId($details['id']);
            $this->image_repo->delete($image);

            return 1;
        } else {
            return 0;
        }
    }

    public function deleteProductUploadForFirstAdd(Request $request) {
        $details = $request->all();

        $filename = $details['name'];
        $path_final_dir = public_path() . '/../../Uploads/' . $details['folder'] . '/';

        $thumb_path = 'thumb/';

        if (File::delete($path_final_dir . $filename)) {
            if (File::delete($path_final_dir . $thumb_path . $filename)) {
                
            }

//            foreach ($details['imgs'] as $key_item => $value_item)
//            {
//                $data_product['product_images'][] = $this->image_repo->ImageOfId($value_item);
//            }
//            $details_temp = $this->product_approved_repo->ProductApprovedOfId($details['product_id']);
//            $this->product_approved_repo->update($details_temp, $data_product);

            $image = $this->image_repo->ImageOfId($details['id']);
            $this->image_repo->delete($image);

            return 1;
        } else {
            return 0;
        }
    }

//    public function upload_document(Request $request)
//    {
//        $file = $request->file('photo');
//        $size = File::size($file);
//        $destinationPath = public_path() . '/Uploads/' . $request['folder'] . '/';
//        @mkdir(public_path() . '/Uploads/' . $request['folder'], 0777);
//
//        $extension = $file->getClientOriginalExtension();
//
//        $filename = str_random(25) . '.' . $extension;
//        $upload_success = $request->file('photo')->move($destinationPath, $filename);
//        if ($upload_success)
//        {
//            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif' || $extension == 'bmp')
//            {
//                @mkdir($destinationPath . 'thumb', 0777);
//                $img = Image::make($destinationPath . $filename);
//                $img->resize(150, 150);
//
//                $img->save($destinationPath . 'thumb/' . $filename);
//            }
//            return response()->json(['filename' => $filename, 'size' => $size, 'name' => $file->getClientOriginalName()]);
//        }
//        else
//        {
//            return 'YEP: Problem in file upload';
//        }
//    }
    #### delete User avatar
    
    public function updateImagePriority(Request $request){
        $images = $request->all();
        $i = 1;
        foreach($images as $image){
            $data = [];
            $data['priority'] = $i;
            $img_obj = $this->image_repo->ImageOfId($image);
            $this->image_repo->update($img_obj,$data);
            $i++;
        }
        return response()->json("Successfully Updated");
    }
}
