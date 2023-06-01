<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Preset;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Session;


class ViewImageController extends Controller
{
    // View image using id
    public function index($image_id, Request $request)
    {
        // if get image id
        if ($image_id) {
            // get image  data from database
            $user_category = Category::find(Auth::user()->category);

            $can_see_all_categories = userHasRole(Auth::user()->permission, array(ADMIN_ROLE)) || Str::lower($user_category->category) === 'all';
            $image = Image::where('image_id', $image_id)->with('user')->first();

            $categories = $can_see_all_categories ?
                Category::select('category', 'id')->where('active', 1)->whereNotIn('category', ['All', 'all', 'ALL'])->orderBy('category', 'asc')->get() :
                Category::select('category', 'id')->where('active', 1)->orderBy('category', 'asc')->where('id', Auth::user()->category)->get();

            $presets = Preset::where('active', 1)->get();
            // if data not null
            if ($image != null) {
                // Views + 1
                $views = $image->views + 1;
                // Update image views
                $updateView = Image::where('image_id', $image_id)->update(['views' => $views]);

                if($request->session()->has('alert-saved')) {
                    $request->session()->flash('alert-saved', 'Guardado');
                }
                return view('pages.viewimage', compact('image', 'presets', 'categories'));
            } else {
                // Abort 404
                return abort(404);
            }
        } else {
            // Abort 404
            return abort(404);
        }
    }

    // View image using id
    public function modal($image_id, $category = '', $preset = '')
    {
        // if get image id
        if ($image_id) {
            // get image  data from database
            $image = Image::where('image_id', $image_id)->with('user')->first();

            $categories = Category::where('active', 1)->get();
            $presets = Preset::where('active', 1)->get();
            $select_category = $category;
            $select_preset = $preset;

            // if data not null
            if ($image != null) {
                // Views + 1
                $views = $image->views + 1;
                // Update image views
                $updateView = Image::where('image_id', $image_id)->update(['views' => $views]);
                return view('pages.modal-image', compact('image', 'presets', 'categories', 'select_category', 'select_preset'));
            } else {
                // Abort 404
                return abort(404);
            }
        } else {
            // Abort 404
            return abort(404);
        }
    }

    // Download image
    public function DownloadImage($image_id)
    {
        // Get image data
        $image = Image::where('image_id', $image_id)->first();
        // if image not null
        if ($image != null) {

            $file_path = $image->image_path;
            $path = 'temp/';
            if (!File::exists($path)) {
                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
            }
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            $fileContents = file_get_contents($file_path, false, stream_context_create($arrContextOptions));
            $ext = pathinfo($file_path, PATHINFO_EXTENSION);
            $filename = date('d-m-Y') . "_" . $image->image_id . "." . $ext;
            if (file_exists($path . $filename)) {
                $delete = File::delete($path . $filename);
            }
            $lastfile = $path . $filename;
            File::put($lastfile, $fileContents);
            $download = \Response::download($lastfile, $filename)->deleteFileAfterSend(true);
            return $download;
        } else {
            // Abort 404
            return abort(404);
        }
    }

    //Change Image
    public function updateImage(Request $request){

        $imageCrop   = $request->new_image;
        $image_id    = $request->image_id;
        $category    = $request->category;
        $tags        = $request->tags;
        $description = $request->description;
        $preset      = $request->preset;
        $type        = $request->ext;
        $path        = "ib/";
        $thumbnail   = $request->thumbnail;
        $gallery     = $request->gallery;

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $imageCrop );
        $imageBinary = base64_decode($data[1]);

        $im = imageCreateFromString($imageBinary);

        if (!$im) {
            die('Base64 value is not a valid image');
        }

        $imageName = $image_id.".".$type;
        $img_file = $path.$imageName;

        switch($type){
            case 'png':
                $result = imagepng($im, $img_file, 5);
            break;

            case 'jpg':
                $result = imagejpeg($im, $img_file, 80);
            break;

            default:
                $result = imagejpeg($im, $img_file, 80);
            break;

        }

        $preset = Preset::findOrFail($preset);


        if($result){
            // file name
            $filename = url($path) . '/' . $imageName;
            $fileSize = filesize($img_file);
            list($w, $h) = getimagesize($img_file);

            $image_query = Image::where('image_id', $image_id);
            $image = clone $image_query->first();


            $approval = $image->approval;

            if ($image->approval == 0 && $gallery == 0 && $thumbnail == 0) {
                $approval = 1;
            }


            $image_query->update([
                'image_path'  => $filename,
                'image_size'  => $fileSize,
                'description' => $description,
                'preset_id'   => $preset->id,
                'preset'      => $preset->value,
                'width'       => $w,
                'height'      => $h,
                'category'    => $category,
                'tags'        => $tags,
                'thumbnail'   => $thumbnail,
                'gallery'     => $gallery,
                'approval'    => $approval,
            ]);

            $this->thumbnail($filename);

            echo $filename;
        } else {
            die('An error has occurred, please try again');
        }

    }

    //Update Image
    public function updateImageInfo(Request $request){

        $image_id    = $request->image_id;
        $category    = $request->category;
        $tags        = $request->tags;
        $thumbnail   = $request->thumbnail;
        $gallery     = $request->gallery;

        $image_query = Image::where('image_id', $image_id);
        $image = clone $image_query->first();


        $approval = $image->approval;

        if ($image->approval == 0 && $gallery == 0 && $thumbnail == 0) {
            $approval = 1;
        }

        $image_query->update([
            'category'   => $category,
            'tags'       => $tags,
            'thumbnail'  => $thumbnail,
            'gallery'    => $gallery,
            'approval'    => $approval,
        ]);

        $request->session()->flash('alert-success', 'true');
        return redirect()->route('ib.view', $image_id);

    }

    public function thumbnail($filename)
    {

        //$filename = '3Gu6cWodtF.png';
        $filename = substr(strrchr($filename, '/'), 1);
        $path = "./ib/";
        $thumbnail = "thumb_".$filename;

        $src = $path.$filename;
        //$src = $filename;

        //Verifico que exista la carpeta
        $dirThumbnails = './ib/thumbnails/';
        if(!is_dir($dirThumbnails)){
            mkdir($dirThumbnails, 0777);
        }

        // File and new size
        list($width, $height) = getimagesize($src);
        $mime = mime_content_type($src);

        $max_width = 320;
        $x_ratio = $max_width / $width;

        $new_height = ceil($x_ratio * $height);
        $new_width = $max_width;

        //Compress Image
        switch($mime){
            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
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
                $quality = 80;
        }

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
            // imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            // $upload = $image($thumb, $path.$image_new, $quality);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $image($thumb, $dirThumbnails.$thumbnail);
        }

    }

    private function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    //Change Image
    public function searchImages(Request $request){

        $category   = $request->category;
        $preset     = $request->preset;
        $tags       = $request->tags;
        $dates      = $request->date;

        $images = Image::when($category, function ($query, $category) {
                        return $query->where('category', $category);
                    });

                    //Preset
                    $images->when($preset, function ($query, $age1) {
                        return $query->whereRaw('ABS(TIMESTAMPDIFF(YEAR, dateborn, CURDATE())) >= '.$age1);
                    });

                    /*
                    //Date
                    $clients->when($age2, function ($query, $age2) {
                        return $query->whereRaw('ABS(TIMESTAMPDIFF(YEAR, dateborn, CURDATE())) <= '.$age2);
                    });

                    //Image
                    if($interest_id != null){
                        $clients->WhereHas('client_interests',function($query) use ($interest_id){
                            $query->where('client_interests.interest_id','=',$interest_id);
                        });
                    }
                    */
        $images = $images->get();

        $images = json_encode($images);

        return $images;

    }

    //Change Image
    public function getImages($category, $preset, $date, $tags){


        $images = Image::when($category, function ($query, $category) {
                        return $query->where('category', $category);
                    });

                    //Preset
                    $images->when($preset, function ($query, $preset) {
                        return $query->where('description', $preset);
                    });

                    /*
                    //Date
                    $clients->when($age2, function ($query, $age2) {
                        return $query->whereRaw('ABS(TIMESTAMPDIFF(YEAR, dateborn, CURDATE())) <= '.$age2);
                    });

                    //Image
                    if($interest_id != null){
                        $clients->WhereHas('client_interests',function($query) use ($interest_id){
                            $query->where('client_interests.interest_id','=',$interest_id);
                        });
                    }
                    */
        $images = $images->get();

        $images = json_encode($images);

        return $images;
    }

    public function duplicateImage(Request $request){

        $image_id    = $request->image_id;
        $category    = $request->category;
        $tags        = $request->tags;
        $description = $request->description;
        $preset      = $request->preset;
        $type        = $request->ext;
        $path        = "ib/";
        $thumbnail   = $request->thumbnail;
        $gallery     = $request->gallery;

        $newImgID = Str::random(10);

        $fileDir = $path.$image_id.'.'.$type;
        $rootPath = realpath($path);

        //Duplico la imagen
        copy($rootPath.'/'.$image_id.'.'.$type, $rootPath.'/'.$newImgID.'.'.$type);

        if (Auth::user()) {$userID = Auth::user()->id;} else { $userID = null;}
        $filename = url($path) . '/' . $newImgID.'.'.$type;

        $image = Image::Where('image_id', $image_id)->first();
        $fileSize = $image->image_size;
        $w = $image->width;
        $h = $image->height;
        $method = 1;

        $data = Image::Create([
            'user_id'    => $userID,
            'image_id'   => $newImgID,
            'image_path' => $filename,
            'image_size' => $fileSize,
            'description'=> "Original Size",
            'width'      => $w,
            'height'     => $h,
            'method'     => $method,
            'category'   => $category,
            'tags'       => $tags,
            'thumbnail'  => $thumbnail,
            'gallery'    => $gallery,

        ]);

        $image = Image::where('image_id', $image_id);
        $image->update([
            'image_parent' => $newImgID,
        ]);

        $image = Image::where('image_parent', $image_id);
        $image->update([
            'image_parent' => $newImgID,
        ]);

        echo $newImgID;
    }
}
