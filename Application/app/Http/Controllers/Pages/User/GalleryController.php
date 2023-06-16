<?php

namespace App\Http\Controllers\Pages\User;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Category;
use App\Models\Preset;
use Auth;
use Illuminate\Support\Facades\File;
use Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    // View user gallery
    public function index()
    {
        $authId = Auth::user()->id; // user id
        $user_category = Category::find(Auth::user()->category);

        $can_see_all_categories = userHasRole(Auth::user()->permission, array(ADMIN_ROLE)) || Str::lower($user_category->category) === 'all';

        //Filtros
        $filter_category = request()->query("c");
        $category = null;
        if (strlen($filter_category) > 0) {
            if ($filter_category > 0) {
                $category = Category::find($filter_category);
                $category = $category->category;
            } else {
                $category = null;
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

        $group = 1;
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
                        ->leftjoin('presets', 'images.preset_id', '=', 'presets.id');

        if (!empty($category) || !empty($client)) {
            $presets  = $presets->where('images.category', empty($category) ? $client : $category);
        }

        $presets = $presets->distinct()->orderBy('presets.preset', 'asc')->get();
        $dates = Image::selectRaw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name')->where('images.active', 1);

        if (!empty($category) || !empty($client)) {
            $dates  = $dates->where('category', empty($category) ? $client : $category);
        }

        $dates = $dates->distinct()->orderBy('year', 'desc')->orderBy('month', 'desc')->get();


        $image_tags = Image::select('tags')->whereNotNull('tags')
                        ->where('images.active', 1)
                        ->where('images.gallery', $group);

        if (!empty($category) || !empty($client)) {
            $image_tags  = $image_tags->where('images.category', empty($category) ? $client : $category);
        }

        if (!empty($preset)) {
            $image_tags  = $image_tags->where('images.preset_id', $preset);
        }

        $image_tags = $image_tags->distinct()->get();

        $tags = array();

        foreach ($image_tags as $arr_tags){
            $aux_tags = explode(',', $arr_tags->tags);

            foreach ($aux_tags as $key => $tag_item) {
                $tags[] = $tag_item;
            }
        }

        $tags = array_unique($tags);
        sort($tags);


        //* * * * * * * * * * * * * * * * * * * * * *//
        //  Obtengo todas las imagenes del usuario  //
        //* * * * * * * * * * * * * * * * * * * * *//
        $images = $can_see_all_categories ? Image::where('active', 1) : Image::where('user_id', $authId)->where('active', 1);

        //Filtro los registros
        //Category
        if (!empty($category) || is_null($category)) {
            if (is_null($category)) {
                $images = $images->whereNull('category');
            } else {
                $images = $images->where('category', $category);
            }
        }

        //Client

        if (!empty($client)) {
            $images = $images->where('category', $client);
        }

        //Preset
        if (!empty($preset) || is_null($preset)) {
            if (is_null($preset)) {
                $images = $images->whereNull('preset_id');
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
        switch($type){
            case 0:

            $images = $images
                        ->where('width', '>', 200)
                        ->where('height', '>', 200)
                        ->where(function ($query) {
                                $query->where('thumbnail', 0)->orWhereNull('thumbnail');
                            });
            $paginate = 72;
            break;

            case 1:
            if (empty($category)) {
                $images = $images
                            ->where(function ($query) {
                                $query->where('width', '<=', 200)->orWhere('height', '<=', 200);
                            })
                            ->orWhere('thumbnail', 1);
            } else {
                $images = $images
                            ->where(function ($query) {
                                $query->where('width', '<=', 200)->orWhere('height', '<=', 200);
                            })
                            ->orWhere(function ($query) use ($category) {
                                $query->where('thumbnail', 1)->where('category', $category);
                            });
            }
            $paginate = 120;
            break;
        }

        //Group
        $images = $images->where('gallery', $group);

        $paginate = 72;
        $images = $images->with('user')
                        ->orderbyDesc('id')
                        ->paginate($paginate)
                        ->appends(request()->query());

        return view('pages.user.gallery', compact('images', 'categories', 'presets', 'dates', 'tags', 'type'));
    }

    // delete image
    public function deleteImage($id)
    {
        $authId = Auth::user()->id; // user id
        // check image data
        $check = Image::where('user_id', $authId)->where('id', $id)->first();
        // get image using id
        $avdata = Image::where('user_id', $authId);
        // if check data is not null
        if ($check != null) {
            // Check if image upload on server or on amazon
            if ($check->method == 1) {
                $image = str_replace(url('/') . '/', '', $check->image_path);
                if (file_exists($image)) {
                    $deleteImage = File::delete($image);
                }
            } elseif ($check->method == 2) {
                // delete image from amazon s3
                $image = pathinfo(storage_path() . $check->image_path, PATHINFO_EXTENSION);
                $awsImage = $check->image_id . '.' . $image; // file name on amazon s3
                if (Storage::disk('s3')->has($awsImage)) {
                    // Delete image from amazon s3
                    $deleteImage = Storage::disk('s3')->delete($awsImage);
                } elseif (Storage::disk('wasabi')->has($awsImage)) {
                    // Delete image from wasabi
                    $deleteImage = Storage::disk('wasabi')->delete($awsImage);
                }
            } else {
                // Error response
                return response()->json(['error' => 'Cannot find file server']);
            }
            // Delete from database
            $delete = Image::where([['user_id', $authId], ['id', $id]])->delete();
            // if deleted
            if ($delete) {
                // success response
                return response()->json([
                    'success' => 'Image deleted successfully',
                    'avdata' => $avdata->count(),
                ]);
            } else {
                // error response
                return response()->json(['error' => 'Delete error please refresh page and try again']);
            }
        } else {
            // error response
            return response()->json(['error' => 'Delete error please refresh page and try again']);
        }
    }
}
