<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Category;
use App\Models\Preset;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    // view landing
    public function landing()
    {
        return view('pages.landing');
    }

    // view home page public
    public function index($client = '')
    {
        if (empty($client)) {
            return redirect()->route('landing');
        }

        //Filtros
        $category = $client;
        $filter_category = "";
        $preset = null;

        $filter_preset = request()->query("p");

        if (strlen($filter_preset) > 0) {
            if ($filter_preset > 0) {
                $preset = Preset::findOrFail($filter_preset);
                $preset = $preset->id;
            } else {
                $preset = null;
            }
        }

        $year = "";
        $month = "";
        $filter_date = request()->query("d");

        if (strlen($filter_date) > 0) {
            $year   = strstr($filter_date, "_", true);
            $month  = substr(strrchr($filter_date, "_"), 1);
        }

        $tag = "";
        $filter_tags = request()->query("t");

        if (strlen($filter_tags) > 0) {
            $tag = strtoupper($filter_tags);
        }

        $type = 0;
        $filter_type = request()->query("s");

        if (strlen($filter_type) > 0) {
            $type = $filter_type;
        }

        $group = 1;
        $filter_group = request()->query("g");

        if (strlen($filter_group) > 0) {
            $group = $filter_group;
        }


        // Dropdowns
        $categories = Image::select('categories.id', 'images.category')
                            ->where('categories.active', 1)
                            ->where('images.active', 1)
                            ->leftjoin('categories', 'images.category', '=', 'categories.category')
                            ->distinct()->orderBy('images.category', 'asc')->get('category');


        $presets = Image::select('presets.id', 'presets.preset', 'presets.value')
                        ->where('images.active', 1)
                        ->leftjoin('presets', 'images.preset_id', '=', 'presets.id');

        if (!empty($filter_category) || !empty($client)) {
            $presets  = $presets->where('images.category', empty($filter_category) ? $client : $filter_category);
        }

        $presets = $presets->distinct()->orderBy('presets.preset', 'asc')->get();


        $dates = Image::selectRaw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name')->where('images.active', 1);

        if (!empty($filter_category) || !empty($client)) {
            $dates  = $dates->where('category', empty($filter_category) ? $client : $filter_category);
        }

        $dates = $dates->distinct()->orderBy('year', 'desc')->orderBy('month', 'desc')->get();


        //Drop Down Tags
        $image_tags = Image::select('tags')->whereNotNull('tags')
                        ->where('images.active', 1)
                        ->where('images.gallery', $group)
                        ->where('images.category', $category)
                        ->where('images.preset_id', $preset);

        switch($type) {
            case 0: //Pictures

            $image_tags = $image_tags->where('width', '>', 200)
                            ->where('height', '>', 200)
                            ->where('thumbnail', 0);
            break;

            case 1: //Thumbnail

            if (empty($category)) {
                $image_tags = $image_tags->where(function($query) {
                                        $query->where('width', '<=', 200)
                                            ->orWhere('height', '<=', 200);
                                    })
                                ->where('thumbnail', 0);
            } else {
                $image_tags = $image_tags->where(function($query) {
                                        $query->where('width', '<=', 200)
                                            ->orWhere('height', '<=', 200);
                                        })
                                ->where('category', $category)
                                ->where('thumbnail', 0);
            }
            break;

            case 2: //Icons

            if (empty($category)) {
                $image_tags = $image_tags->Where('thumbnail', 1);
            } else {
                $image_tags = $image_tags->where('thumbnail', 1)
                                ->where('category', $category);
            }
            break;
        }

        $image_tags = $image_tags->distinct()->get();
        $tags = array();

        foreach($image_tags as $arr_tags){
            $aux_tags = explode(',', $arr_tags->tags);
            foreach($aux_tags as $key => $tag_item){
                $tags[] = $tag_item;
            }
        };

        $tags = array_unique($tags);
        sort($tags);


        //* * * * * * * * * * * * * * * * * * * * *//
        //        Obtengo todas las imagenes       //
        //* * * * * * * * * * * * * * * * * * * * *//
        $images = Image::where('method', 1)->where('active', 1);

        //Filtro los registros
        //Category
        if (!empty($category) || is_null($category)) {
            if (is_null($category)) {
                $images = $images->whereNull('category');
            } else {
                $images = $images->where('category', $category);
            };
        }

        //Client
        if (!empty($client)) {
            $images = $images->where('category', $client);
        }


        //Preset
        if (!empty($preset) || is_null($preset)) {
            if (is_null($preset)) {
                $images = $images->whereNull('preset');
            } else {
                $images = $images->where('preset_id', $preset);
            }
        }

        //Date
        if (!empty($year)) {
            $images = $images->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month);
        }

        //Tags
        if (!empty($tag)) {
            $images = $images->where('tags', 'like', '%'.$tag.'%');
        }

        //Type
        switch($type) {
            case 0: //Pictures
            $images = $images->where('width', '>', 200)
                            ->where('height', '>', 200)
                            ->where('thumbnail', 0);
            $paginate = 72;
            break;

            case 1: //Thumbnail
            if (empty($category)) {
                $images = $images->where(function ($query) {
                                        $query->where('width', '<=', 200)
                                            ->orWhere('height', '<=', 200);
                                    })
                                ->where('thumbnail', 0);
            } else {
                $images = $images->where(function ($query) {
                                        $query->where('width', '<=', 200)
                                            ->orWhere('height', '<=', 200);
                                        })
                                ->where('category', $category)
                                ->where('thumbnail', 0);
            }
            $paginate = 120;
            break;

            case 2: //Icons
            if (empty($category)) {
                $images = $images->Where('thumbnail', 1);
            } else {
                $images = $images->where('thumbnail', 1)
                                ->where('category', $category);
            }
            $paginate = 120;
            break;
        }

        //Group
        $images = $images->where('gallery', $group);

        $images = $images->with('user')
                        ->orderbyDesc('id')
                        ->paginate($paginate)
                        ->appends(request()->query());

        return view('pages.views', compact('images', 'categories', 'presets', 'dates', 'tags', 'type', 'client'));
    }

    public function gallery()
    {
        // user images data
        $user_category = Category::find(Auth::user()->category);

        $can_see_all_categories = userHasRole(Auth::user()->permission, array(ADMIN_ROLE)) || Str::lower($user_category->category) === 'all';

        //Filtros
        $filter_category = request()->query("c");
        $category = "no_category";

        if (strlen($filter_category) > 0) {
            if ($filter_category > 0) {
                $category = Category::find($filter_category)->category;
            } elseif ($filter_category == 0) {
                $category = null;
            } else {
                $category = "no_category";
            }
        }

        $preset = null;
        $filter_preset = request()->query("p");

        if (strlen($filter_preset) > 0) {
            if ($filter_preset > 0) {
                $preset = Preset::findOrFail($filter_preset);
                $preset = $preset->id;
            } else {
                $preset = null;
            }
        }

        $year = "";
        $month = "";
        $filter_date = request()->query("d");

        if (strlen($filter_date) > 0) {
            $year   = strstr($filter_date, "_", true);
            $month  = substr(strrchr($filter_date, "_"), 1);
        }

        $tag = "";
        $filter_tags = request()->query("t");

        if (strlen($filter_tags) > 0) {
            $tag = strtoupper($filter_tags);
        }

        $type = 0;
        $filter_type = request()->query("s");

        if (strlen($filter_type) > 0) {
            $type = $filter_type;
        }

        $group = 0;
        $filter_group = request()->query("g");

        if (strlen($filter_group) > 0) {
            $group = $filter_group;
        }

        // Dropdowns
        $categories = $can_see_all_categories ?
            Category::select('category', 'id')
                ->where('active', 1)
                ->whereNotIn('category', ['All', 'all', 'ALL'])
                ->orderBy('category', 'asc')
                ->get() :
            Category::select('category', 'id')
                ->where('active', 1)
                ->orderBy('category', 'asc')
                ->where('id', Auth::user()->category)
                ->get();


        $presets = Image::select('presets.id', 'presets.preset', 'presets.value')
                        ->where('images.active', 1)
                        ->where('images.category', $category)
                        ->leftjoin('presets', 'images.preset_id', '=', 'presets.id');

        $presets = $presets->distinct()->orderBy('presets.preset', 'asc')->get();


        $dates = Image::selectRaw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name')->where('images.active', 1);

        if (!empty($category)) {
            $dates  = $dates->where('category', $category);
        }

        $dates = $dates->distinct()->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        //Drop Down Tags
        $image_tags = Image::select('tags')->whereNotNull('tags')
                        ->where('images.active', 1)
                        ->where('images.gallery', $group)
                        ->where('images.category', $category)
                        ->where('images.preset_id', $preset);

        switch($type) {
            case 0: //Pictures

            $image_tags = $image_tags->where('width', '>', 200)->where('height', '>', 200)->where('thumbnail', 0);
            break;

            case 1: //Thumbnail

            if (empty($category)) {
                $image_tags = $image_tags
                                ->where(function($query) {
                                    $query->where('width', '<=', 200)->orWhere('height', '<=', 200);
                                })
                                ->where('thumbnail', 0);
            } else {
                $image_tags = $image_tags
                                ->where(function($query) {
                                    $query->where('width', '<=', 200)->orWhere('height', '<=', 200);
                                })
                                ->where('category', $category)
                                ->where('thumbnail', 0);
            }
            break;

            case 2: //Icons
            if (empty($category)) {
                $image_tags = $image_tags->Where('thumbnail', 1);
            } else {
                $image_tags = $image_tags->where('thumbnail', 1)->where('category', $category);
            }
            break;
        }

        $image_tags = $image_tags->distinct()->get();

        $tags = array();

        foreach ($image_tags as $arr_tags) {
            $aux_tags = explode(',', $arr_tags->tags);

            foreach ($aux_tags as $key => $tag_item) {
                $tags[] = $tag_item;
            }
        }

        $tags = array_unique($tags);
        sort($tags);

        //* * * * * * * * * * * * * * * * * * * * *//
        //        Obtengo todas las imagenes       //
        //* * * * * * * * * * * * * * * * * * * * *//
        $images = Image::where('method', 1)->where('active', 1);
        
        if ($group == 1) {
            $images = $images->where('approval', 1);
        }

        //Filtro los registros

        //Category
        if (!empty($category) || is_null($category)) {
            if (is_null($category)) {
                $images = $images->whereNull('category');
            } else {
                $images = $images->where('category', $category);
            }
        }

        //Preset
        if (!empty($preset) || is_null($preset)) {
            if (is_null($preset)) {
                $images = $images->whereNull('preset');
            } else {
                $images = $images->where('preset_id', $preset);
            }
        }

        //Date
        if (!empty($year)) {
            $images = $images->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }

        //Tags
        if (!empty($tag)) {
            $images = $images->where('tags', 'like', '%'.$tag.'%');
        }

        //Type
        switch($type) {
            case 0: //Pictures

            $images = $images->where('width', '>', 200)->where('height', '>', 200)->where('thumbnail', 0);
            $paginate = 72;
            break;

            case 1: //Thumbnail

            if (empty($category)) {
                $images = $images
                            ->where(function ($query) {
                                $query->where('width', '<=', 200)->orWhere('height', '<=', 200);
                            })
                            ->where('thumbnail', 0);
            } else {
                $images = $images
                            ->where(function ($query) {
                                $query->where('width', '<=', 200)->orWhere('height', '<=', 200);
                            })
                            ->where('category', $category)
                            ->where('thumbnail', 0);
            }
            $paginate = 120;
            break;

            case 2: //Icons

            if (empty($category)) {
                $images = $images->Where('thumbnail', 1);
            } else {
                $images = $images->where('thumbnail', 1)->where('category', $category);
            }
            $paginate = 120;
            break;
        }

        if (!empty($tag)) {
            $images = $images->where('tags', 'like', '%'.$tag.'%');
        }

        //Group
        $images = $images->where('gallery', $group);

        $images = $images->with('user')
                        ->orderbyDesc('id')
                        ->paginate($paginate)
                        ->appends(request()->query());

        //Busco las categorias Featured
        $featured_categories = $can_see_all_categories ?
            Category::where('active', 1)
                ->where('featured', 1)
                ->orderBy('category', 'asc')
                ->get() :
            Category::where('active', 1)
                ->where('featured', 1)
                ->where('id', Auth::user()->category)
                ->orderBy('category', 'asc')
                ->get();


        $featured_images = array();
        //Busco las imágenes que de las categorías featured
        foreach ($featured_categories as $featured_category) {

            $featured_image = Image::where('method', 1)
                            ->where('category', $featured_category->category)
                            ->where('active', 1)
                            ->where('gallery', 1)
                            ->where('approval', 1)
                            ->where(function ($query) {
                                $query->where('thumbnail', 0) ->orWhereNull('thumbnail');
                            })
                            ->orderbyDesc('id')->take(5)->get();

            foreach ($featured_image as $image_feature) {
                $featured_images[] = array(
                    'category'    => $image_feature->category,
                    'image_id'    => $image_feature->image_id,
                    'image_path'  => $image_feature->image_path,
                    'description' => $image_feature->description
                );
            }

        }


        return view('pages.gallery', compact('images', 'categories', 'presets', 'dates', 'tags', 'type', 'group', 'category', 'featured_categories', 'featured_images'));
    }

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

    // View image using id
    public function view($image_id)
    {
        // if get image id
        if ($image_id) {
            // get image  data from database
            $image = Image::where('image_id', $image_id)->with('user')->first();
            $categories = Category::where('active', 1)->get();
            $presets = Preset::where('active', 1)->get();
            // if data not null
            if ($image != null) {
                // Views + 1
                $views = $image->views + 1;
                $client = $image->category;
                // Update image views
                $updateView = Image::where('image_id', $image_id)->update(['views' => $views]);
                return view('pages.preview', compact('image', 'presets', 'categories', 'client'));
            } else {
                // Abort 404
                return abort(404);
            }
        } else {
            // Abort 404
            return abort(404);
        }
    }

    public function upload()
    {
        return view('pages.home');
    }

    public function uploadModal($category = 0, $preset = 0)
    {
        return view('pages.upload', compact('category', 'preset'));
    }

    public function thumbnail($filename)
    {

        $file = "pS1IE8D49P.jpeg";

        $filename = "http://media.test/ib/pS1IE8D49P.jpeg";

        echo $filename;

        echo "<br>";

        $new_file = substr(strrchr($filename, '/'), 1);

        echo $new_file;

        // $path = "./ib/";
        // $image_new = "thumb_".$filename;

        // $src = $path.$filename;

        // // File and new size
        // list($width, $height) = getimagesize($src);
        // $mime = mime_content_type($src);

        // $max_width = 320;
        // $x_ratio = $max_width / $width;

        // $new_height = ceil($x_ratio * $height);
        // $new_width = $max_width;

        // //Compress Image
        // switch($mime){
        //     case 'image/jpeg':
        //         $image_create = "imagecreatefromjpeg";
        //         $image = "imagejpeg";
        //         $quality = 80;
        //         break;

        //     case 'image/png':
        //         $image_create = "imagecreatefrompng";
        //         $image = "imagepng";
        //         break;

        //     case 'image/gif':
        //         $image_create = "imagecreatefromgif";
        //         $image = "imagegif";
        //         break;

        //     default:
        //         $image_create = "imagecreatefromjpeg";
        //         $image = "imagejpeg";
        //         $quality = 80;
        // }

        // $thumb = imagecreatetruecolor($new_width, $new_height);
        // $source = $image_create($src);

        // if($mime == 'image/gif'){
        //     //No hace nada
        // } elseif($mime == 'image/png') {
        //     $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
        //     imagefill($thumb, 0, 0, $transparent);
        //     imagecolortransparent($thumb, $transparent);
        //     imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        //     $upload = $image($thumb, $path."/".$image_new);

        // } else {
        //     imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        //     $upload = $image($thumb, $path.$image_new, $quality);
        // }

    }

    public function generateThumbnails()
    {

        $dir = './ib/';
        $images = scandir($dir, 0);
        $images = array_diff($images, array('.', '..', '.DS_Store', 'thumbnails'));

        //Verifico que exista la carpeta
        $dirThumbnails = './ib/thumbnails/';
        if(!is_dir($dirThumbnails)){
            mkdir($dirThumbnails, 0777);
        }

        //Recorro el directorio de imágenes
        foreach ($images as $image) {
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
        }
    }

    public function sessionVariable($var)
    {
        //alert-saved

        session(['alert-saved' => $var]);
    }

    public function category($category)
    {
        $category = Category::where('category', $category)->first();

        echo $category->id;

    }

    public function duplicateImage($image_id)
    {

        $path = "ib/";

        $image = Image::findOrFail($image_id);
        $image_path = $image->image_path;

        $filename = substr(strrchr($image_path, "/"), 1);
        $ext = substr(strrchr($filename, "."), 1);

        //dd($filename, $ext);

        $newImgID = Str::random(10);

        $fileDir = $path.$filename;
        $rootPath = realpath($path);

        //Duplico la imagen
        copy($rootPath.'/'.$filename, $rootPath.'/'.$newImgID.'.'.$ext);

        //Duplico el thumbnail
        $dirThumbnails = './ib/thumbnails/';
        $rootThumbnails = realpath($dirThumbnails);
        if(file_exists($dirThumbnails.'thumb_'.$filename)){
            copy($rootThumbnails.'/'.'thumb_'.$filename, $rootThumbnails.'/'.'thumb_'.$newImgID.'.'.$ext);
        } else {
            $this->thumbnail($newImgID.'.'.$ext);
        }

        if (Auth::user()) {$userID = Auth::user()->id;} else { $userID = null;}
        $filename = url($path) . '/' . $newImgID.'.'.$ext;

        $fileSize = $image->image_size;
        $w = $image->width;
        $h = $image->height;
        $method = 1;

        $data = Image::Create([
            'user_id'      => $userID,
            'image_id'     => $newImgID,
            'image_path'   => $filename,
            'image_size'   => $fileSize,
            'description'  => "Original Size",
            'width'        => $w,
            'height'       => $h,
            'method'       => $method,
            'category'     => $image->category,
            'tags'         => $image->tags,
            'thumbnail'    => $image->thumbnail,
            'image_parent' => $image_id,
        ]);

        echo $newImgID;
    }

    public function noCategory() {
        return view('pages.no-category');
    }

}
