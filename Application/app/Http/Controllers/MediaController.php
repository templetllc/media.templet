<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Category;
use App\Models\Preset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    //
    public function getImages($category, $preset, $date, $tags, $width = 0, $height = 0, $page = 1)
    {
        $current = $page;
        /*      
        $_category = "";
        $filter_category = $category;
        if(strlen($filter_category) > 0){
            $_category = Category::find($filter_category);
            $_category = $_category->category;
        }

        $_preset = "";
        $filter_preset = $preset;
        if($filter_preset > 0){
            $_preset = Preset::where('value', $filter_preset)->first();
            //$_preset = Preset::findOrFail($filter_preset);
            $_preset = $_preset->id;
        }
        */

        $images = Image::where('method', 1)
                        ->where('active', 1);

        //Filtro los registros
        //Category
        if(!empty($category)){
            if($category == -1){
                $images = $images->whereNull('category');
            } else {
                $images = $images->where('category', $category);
            }
        }

        //Preset
        if(!empty($preset)){
            if($preset == -1){
                $images = $images->whereNull('preset');
            } else {
                $images = $images->where('preset', $preset);
            }
        }

        //Width
        if(!empty($width)){
            $images = $images->where('width', $width);
        }

        //Height
        if(!empty($height)){
            $images = $images->where('height', $height);
        }

        //Tags
        if(!empty($tags)){
            $images = $images->where('tags', 'like', '%'.$tags.'%');
        }
        

        /*$images = Image::when($category, function ($query, $category) {
                        return $query->where('category', $category);
                    });

                    //Preset
                    $images->when($preset, function ($query, $preset) {
                        return $query->where('preset', $preset);
                    });*/

        
        //$images = $images->orderBy('created_at', 'desc')->get();


        $pagination = 20;

        $records = $images->orderBy('created_at', 'desc')->get();
        
        $total_pages = ceil(count($records) / $pagination);

        $page = $pagination * ($page - 1);

        $images = $images->orderBy('created_at', 'desc')->skip($page)->limit($pagination)->get();
                    
        $data = array();
        $data[] = array(
            'data'    => $images,
            'current' => $current,
            'total'   => $total_pages
        );
                    

        $images = json_encode($data);

        return $images;

    }

    public function getCategories()
    {

        $categories = Image::select('categories.id', 'images.category')
                            ->leftjoin('categories', 'images.category', '=', 'categories.category')
                            ->leftjoin('presets', 'images.preset_id', '=', 'presets.id')
                            ->where('categories.active', 1)
                            ->where('presets.addin', 1)
                            ->where('images.active', 1)
                            ->orWhere(function($query){
                                $query->where('images.thumbnail', 1)
                                        ->where('images.active', 1)
                                        ->whereNotNull('images.category');
                            })
                            ->distinct()->orderBy('images.category', 'asc')->get('category');
        
        //$categories = Image::select('category')->distinct()->orderBy('category', 'asc')->get('category');

        $categories = json_encode($categories);

        return $categories;

    }

    public function getPreset($category = "")
    {

        $presets = Image::select('presets.id', 'presets.preset', 'presets.value')
                        ->leftjoin('presets', 'images.preset_id', '=', 'presets.id')
                        ->where('images.active', 1)
                        ->where('presets.addin', 1);
        if(!empty($category)){
            $presets = $presets->where('images.category', $category);
        }
          
        $presets = $presets->distinct()->orderBy('presets.preset', 'asc')->get();

        $presets = json_encode($presets);

        return $presets;

    }

    public function getTags($category = "", $view = 0)
    {

        $image_tags = Image::select('tags')->whereNotNull('tags')
                        ->leftjoin('presets', 'images.preset_id', '=', 'presets.id')
                        ->where('images.active', 1);
                        
        if(!empty($category)){
            $image_tags  = $image_tags->where('images.category', $category);
        }
        if(!empty($view)){
            $image_tags  = $image_tags->where(function($query) {
                                $query->where('presets.addin', 1)
                                    ->orWhere('thumbnail', 1);
                            });
        } else {
            $image_tags  = $image_tags->where('presets.addin', 1);
        }

        $image_tags = $image_tags->distinct()->get();

        $tags = array();
        foreach($image_tags as $arr_tags){
            $aux_tags = explode(',', $arr_tags->tags);
            foreach($aux_tags as $key => $tag_item){
                $tags[] = strtolower($tag_item);
            }
        };

        $tags = array_unique($tags);
        sort($tags);

        $tags = json_encode($tags);

        return $tags;

    }

    public function thumbExists($images)
    {
        if(file_exists('ib/thumbnails/thumb_'.$images)){
            echo 1;
        } else {
            echo 0;
        }
    }

    public function getImagesAddin($category, $preset, $date, $tags, $view, $page = 1)
    {
        $current = $page;
        $images = Image::where('method', 1)->where('active', 1);

        //Filtro los registros
        //Category
        if(!empty($category)){
            // if($category == -1){
            //     $images = $images->whereNull('category');
            // } else {
            //    $images = $images->where('category', $category);
            //}
        } 

        $images = $images->where('category', $category);

        //Preset
        if(!empty($preset)){
            if($preset == -1 || $view == 1){
                $images = $images->whereNull('preset');
            } else {
                $images = $images->where('preset', $preset);
            }
        } else {
            if($view == 0){
                $images = $images->whereIn('preset_id', function($query){
                                        $query->select('id')
                                            ->from('presets')
                                            ->where('active', 1)
                                            ->where('addin', 1);
                                    });
            }
        }

        //Tags
        if(!empty($tags)){
            $images = $images->where('tags', 'like', '%'.$tags.'%');
        }

        //View
        switch($view){
            case 0:
            $images = $images->where('width', '>', 200)
                            ->where('height', '>', 200)
                            ->where(function($query) {
                                        $query->where('thumbnail', 0)
                                            ->orWhereNull('thumbnail');
                                    });

            $pagination = 30;
            break;

            case 1:
            if(empty($category)){
                $images = $images->where('thumbnail', 1);
            } else {
                $images = $images->where('thumbnail', 1)
                                    ->where('category', $category);
            }
            // if(empty($category)){
            //     $images = $images->where(function($query) {
            //                             $query->where('width', '<=', 200)
            //                                 ->orWhere('height', '<=', 200);
            //                         })
            //                     ->orWhere('thumbnail', 1);
            // } else {
            //     $images = $images->where(function($query) {
            //                             $query->where('width', '<=', 200)
            //                                 ->orWhere('height', '<=', 200);
            //                         })
            //                     ->orWhere(function($query) use ($category) {
            //                             $query->where('thumbnail', 1)
            //                                 ->where('category', $category);
            //                         });
            // }

                          
            $pagination = 40;
            break;
        }

        $records = $images->orderBy('created_at', 'desc')->get();
        
        $total_pages = round(count($records) / $pagination);

        $page = $pagination * ($page - 1);

        $images = $images->orderBy('created_at', 'desc')->skip($page)->limit($pagination)->get();
                    
        $data = array();
        $data[] = array(
            'data'    => $images,
            'current' => $current,
            'total'   => $total_pages
        );
                    
        $images = json_encode($data);

        return $images;
    }

    public function getImagesTest($category, $preset, $date, $tags, $width = 0, $height = 0, $page = 1)
    {
        $current = $page;
        /*      
        $_category = "";
        $filter_category = $category;
        if(strlen($filter_category) > 0){
            $_category = Category::find($filter_category);
            $_category = $_category->category;
        }

        $_preset = "";
        $filter_preset = $preset;
        if($filter_preset > 0){
            $_preset = Preset::where('value', $filter_preset)->first();
            //$_preset = Preset::findOrFail($filter_preset);
            $_preset = $_preset->id;
        }
        */

        $images = Image::where('method', 1);

        //Filtro los registros
        //Category
        if(!empty($category)){
            if($category == -1){
                $images = $images->whereNull('category');
            } else {
                $images = $images->where('category', $category);
            }
        }

        //Preset
        if(!empty($preset)){
            if($preset == -1){
                $images = $images->whereNull('preset');
            } else {
                $images = $images->where('preset', $preset);
            }
        }

        //Width
        if(!empty($width)){
            $images = $images->where('width', $width);
        }

        //Height
        if(!empty($height)){
            $images = $images->where('height', $height);
        }

        //Tags
        if(!empty($tags)){
            $images = $images->where('tags', 'like', '%'.$tags.'%');
        }
        

        /*$images = Image::when($category, function ($query, $category) {
                        return $query->where('category', $category);
                    });

                    //Preset
                    $images->when($preset, function ($query, $preset) {
                        return $query->where('preset', $preset);
                    });*/

        
        //$images = $images->orderBy('created_at', 'desc')->get();


        $pagination = 20;

        $records = $images->orderBy('created_at', 'desc')->get();
        
        $total_pages = ceil(count($records) / $pagination);

        $page = $pagination * ($page - 1);

        $images = $images->orderBy('created_at', 'desc')->skip($page)->limit($pagination)->get();
                    
        $data = array();
        $data[] = array(
            'data'    => $images,
            'current' => $current,
            'total'   => $total_pages,
            'records' => count($records) / $pagination
        );
                    

        $images = json_encode($data);

        return $images;

    }

    public function setImages(Request $request)
    {
        
        $id = $request['id'];

        $images = Image::findOrFail($id);

        if(isset($request['description'])){
            $images->update(['description_ai' => $request['description']]);
        }

        if(isset($request['tags'])){
            $images->update(['tags_ai' => $request['tags']]);
        }

        

    }
}
