<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Setting;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Storage;

class UploadController extends Controller
{
    // Upload images
    public function Upload(Request $request)
    {
        // Settings information
        $settings = Setting::find(1);
        try {
            // validate uploads
            $validator = \Validator::make($request->all(), [
                'uploads' => ['max:' . $settings->max_filesize . '000', 'mimes:jpeg,png,jpg,gif'],
            ]);

            // if validate fails
            if ($validator->fails()) {
                // errors array
                $response = array(
                    'type' => 'error',
                    'errors' => $validator->errors()->all(),
                );
                // error response
                return response()->json($response);
            } else {
                // request has file
                if ($request->hasFile('uploads')) {
                    // get fie size
                    $fileSize = $request->file('uploads')->getSize();
                    // give image new name
                    $string = Str::random(10);
                    // new image name
                    $imageName = $string . '.' . $request->file('uploads')->getclientoriginalextension();
                    // check if amazon s3 enabled
                    if ($settings->storage == 2) {
                        // Storage image to amazon S3
                        $upload = Storage::disk('s3')->put($imageName, file_get_contents($request->file('uploads')), 'public');
                        // file name
                        $filename = Storage::disk('s3')->url($imageName);
                        // method
                        $method = 2;
                    } elseif ($settings->storage == 3) { // if wasabi is enabled
                        // Storage image to wasabi
                        $upload = Storage::disk('wasabi')->put($imageName, file_get_contents($request->file('uploads')), 'public');
                        // file name
                        $filename = Storage::disk('wasabi')->url($imageName);
                        // method
                        $method = 2;
                    } else {
                        // upload path
                        $path = 'ib/';
                        // if path not exists create it
                        if (!File::exists($path)) {
                            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                        }
                        
                        //Compress Image
                        $mime = $request->file('uploads')->getMimeType();
                        switch($mime){ 
                            case 'image/jpeg':
                                $image_create = "imagecreatefromjpeg";
                                $image = "imagejpeg";
                                $quality = 80;
                                break; 

                            case 'image/png': 
                                $newImg = imagecreatefrompng($request->file('uploads'));
                                $image_create = "imagecreatefrompng";
                                $image = "imagepng";
                                $quality = 6;
                                break; 

                            case 'image/gif':
                                $image_create = "imagecreatefromgif";
                                $image = "imagegif";
                                break; 

                            default: 
                                $image_create = "imagecreatefromjpeg";
                                $image = "imagejpeg";
                                $quality = 80;
                        }

                        list($width, $height) = getimagesize($request->file('uploads'));
                        $max_width = 1280;
                        $max_height = 900;

                        $x_ratio = $max_width / $width;
                        $y_ratio = $max_height / $height;

                        if( ($width <= $max_width) && ($height <= $max_height) ){
                            $width_new = $width;
                            $height_new = $height;
                        }
                        elseif (($x_ratio * $height) < $max_height){
                            $height_new = ceil($x_ratio * $height);
                            $width_new = $max_width;
                        }
                        else{
                            $width_new = ceil($y_ratio * $width);
                            $height_new = $max_height;
                        }

                        //$dst_img = imagecreatetruecolor($width_new, $height_new);
                        $dst_img = imagecreatetruecolor($width, $height);
                        $src_img = $image_create($request->file('uploads'));

                        if($mime == 'image/gif'){
                            header('Content-Type: image/gif');
                            $image = $request->file('uploads');
                            $upload = $image->move($path, $imageName);
                        } elseif($mime == 'image/png') {

                            //$transindex = imagecolortransparent($dst_img);
                            //echo "transindex = ".$transindex;
                            // $transparent = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
                            // imagefill($dst_img, 0, 0, $transparent);
                            // imagecolortransparent($dst_img, $transparent);
                            imagealphablending($src_img, false);
                            imagesavealpha($src_img, true);
                            //imagecopyresampled($src_img, $src_img, 0, 0, 0, 0, $width_new, $height_new, $width, $height);    
                            
                            $upload = $image($src_img, $path."/".$imageName);

                        } else {
                            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $width_new, $height_new, $width, $height);    
                            $upload = $image($src_img, $path."/".$imageName);
                        }

                        if($dst_img)imagedestroy($dst_img);
                        if($src_img)imagedestroy($src_img);

                        // file name
                        $filename = url($path) . '/' . $imageName;                        
                        
                        //Compress TinyPNG
                        // if($width > 600){
                        //     $result = $this->compressImage($filename);
                        //     $json = json_decode($result);

                        //     $imageCompress = $json->output->url;
                        //     file_put_contents($path.$imageName, file_get_contents($imageCompress));
                        // }

                        // method server host
                        $method = 1;
                    }

                    // if image uploded
                    if ($upload) {
                        // if user auth get user id
                        if (Auth::user()) {$userID = Auth::user()->id;} else { $userID = null;}
                        // create new image data
                        $img_file = $path.$imageName;
                        $fileSize = filesize($img_file);
                        list($w, $h) = getimagesize($img_file);

                        $data = Image::create([
                            'user_id'    => $userID,
                            'image_id'   => $string,
                            'image_path' => $filename,
                            'image_size' => $fileSize,
                            'description'=> "Original Size",
                            'width'      => $w,
                            'height'     => $h,
                            'method'     => $method,
                        ]);

                        //Create Thumbnail
                        $this->thumbnail($imageName);

                        // if image data created
                        if ($data) {
                            // success array
                            $response = array(
                                'type' => 'success',
                                'msg' => 'success',
                                'data' => array('id' => $string),
                            );
                            // success response
                            return response()->json($response);
                        } else {
                            if (file_exists('ib/' . $filename)) {$delete = File::delete('ib/' . $filename);}
                            // error response
                            return response()->json(array(
                                'type' => 'error',
                                'errors' => 'Opps !! Error please refresh page and try again.',
                            ));
                        }
                    } else {
                        // error response
                        return response()->json(array(
                            'type' => 'error',
                            'errors' => 'Upload error.',
                        ));
                    }
                } else {
                    // error response
                    return response()->json(array(
                        'type' => 'error',
                        'errors' => 'Illegal Request.',
                    ));
                }
            }
        } catch (\Exception$e) {
            // error response
            return response()->json(array(
                'type' => 'error',
                'errors' => 'Server error please refresh page and try again. '.$e,
            ));
        }
    }

    public function compressImage($image){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.tinify.com/shrink',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'
        {
          "source": {
            "url": "'.$image.'"
          }
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic YXBpOlBjUDA5UFMybDYzdEtYQnNSbU5ZY04wV1IzQm5WQzU0'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function thumbnail($image) {

        $dir = './ib/';
        
        //Verifico que exista la carpeta
        $dirThumbnails = './ib/thumbnails/';
        if(!is_dir($dirThumbnails)){
            mkdir($dirThumbnails, 0777);
        }

        //Verifico si existe el thumbnail para la imagen
        if(!file_exists($dirThumbnails.$image)){
            $src = $dir.$image;
            $thumbnail = "thumb_".$image;

            // File and new size
            list($width, $height) = getimagesize($src);
            $mime = mime_content_type($src);

            $max_width = 310;
            $x_ratio = $max_width / $width;
            
            $new_height = ceil($x_ratio * $height);
            $new_width = $max_width;

            //Compress Image
            switch($mime){ 
                case 'image/jpeg':
                    $image_create = "imagecreatefromjpeg";
                    $image = "imagejpeg";
                    break; 

                case 'image/png': 
                    $image_create = "imagecreatefrompng";
                    $image = "imagepng";
                    break; 

                case 'image/gif':
                    $image_create = "imagecreatefromgif";
                    $image = "imagegif";
                    break; 

                default: 
                    $image_create = "imagecreatefromjpeg";
                    $image = "imagejpeg";
            }

            //Si la nueva imagen es mas pequeña que la medida minima
            if($width > $max_width){

                $thumb = imagecreatetruecolor($new_width, $new_height); 
                $source = $image_create($src);

                if($mime == 'image/gif'){
                    //No hace nada
                } elseif($mime == 'image/png') {

                    imagealphablending($thumb, false);
                    imagesavealpha($thumb, true);

                    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height); 
                    
                    $image($thumb, $dirThumbnails.$thumbnail);

                } else {
                    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);    
                    $image($thumb, $dirThumbnails.$thumbnail);
                }
            }

        }

    } //End Thumbnail
}
