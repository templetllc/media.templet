<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Category;
use App\Models\Preset;
use Auth;
use Illuminate\Support\Facades\File;
use Storage;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Setting;

class ApprovalController extends Controller
{

    public function approvals($status = -1)
    {

        if(empty(Auth::user()->category))
        {
            return redirect()->route('landing');
        }

        //* * * Categoría del Usuario * * */
        $category_id = Auth::user()->category;
        $category = Category::where('id', $category_id)->first();
        $category = $category->category;

        //* * * * * * * * * * * * * * * * * * * * *//
        //        Obtengo todas las imagenes       //
        //* * * * * * * * * * * * * * * * * * * * *//
        $images = Image::where('method', 1)
                        ->where('active', 1)
                        ->where('category', $category);

        //Counts
        $images_count = Image::where('method', 1)
                        ->where('active', 1)
                        ->where('category', $category);

        //Filtro los registros
        $group = 1;     //Gallery
        $type = 0;      //Pictures    
        $tag = null;


        //Tags
        if(!empty($tag)){
            $images = $images->where('tags', 'like', '%'.$tag.'%');
        }

        //Type
        switch($type){
            case 0: //Pictures
            $images = $images->where('width', '>', 200)
                            ->where('height', '>', 200)
                            ->where(function($query) {
                                        $query->where('thumbnail', 0)
                                            ->orWhereNull('thumbnail');
                                    });

            $images_count = $images_count->where('width', '>', 200)
                            ->where('height', '>', 200)
                            ->where(function($query) {
                                        $query->where('thumbnail', 0)
                                            ->orWhereNull('thumbnail');
                                    });
            $paginate = 30;
            break;

            case 1: //Thumbnail
            if(empty($category)){
                $images = $images->where(function($query) {
                                        $query->where('width', '<=', 200)
                                            ->orWhere('height', '<=', 200);
                                    })
                                ->where('thumbnail', 0);
            } else {
                $images = $images->where(function($query) {
                                        $query->where('width', '<=', 200)
                                            ->orWhere('height', '<=', 200);
                                        })
                                ->where('category', $category)
                                ->where('thumbnail', 0);
            }
            $paginate = 120;
            break;

            case 2: //Icons
            if(empty($category)){
                $images = $images->Where('thumbnail', 1);
            } else {
                $images = $images->where('thumbnail', 1)
                                ->where('category', $category);
            }
            $paginate = 120;
            break;
        }


        //Status
        if($status > -1){
            $images = $images->where('approval', $status);
        }

        //Group
        $images = $images->where('gallery', $group);
        $images_count = $images_count->where('gallery', $group);

        $images = $images->with('user')
                        ->orderbyDesc('id')
                        ->paginate($paginate)
                        ->appends(request()->query());

        $images_count = $images_count->with('user')
                        ->orderbyDesc('id')->get();

        
        $counts = count($images_count);
        $count_approval = $images_count->countBy(function ($items) {
            return $items['approval'];
        });
        
        return view('pages.approval', compact('images', 'counts', 'count_approval'));
    }

    public function approve($id)
    {
        $updateApprove = Image::where('id', $id)->update(['approval' => 1]);

    }

    public function unapprove($id)
    {
        $updateUnapprove = Image::where('id', $id)->update(['approval' => 0]);

    }

    public function detail($id)
    {
        if(empty($id)) { 
            return redirect()->route('approvals');
        }
        
        /* * * Categoría del Usuario * * */
        $category_id = Auth::user()->category;
        $category = Category::where('id', $category_id)->first();
        $category = $category->category;

        //* * * * * * * * * * * * * * * * * * * * *//
        //        Obtengo todas las imagenes       //
        //* * * * * * * * * * * * * * * * * * * * *//
        $image = Image::where('id', $id)
                        ->where('active', 1)
                        ->where('category', $category)->first();

        return view('pages.detail', compact('image'));

    }
}
