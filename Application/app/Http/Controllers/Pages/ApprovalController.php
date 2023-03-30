<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Category;
use Auth;
use Illuminate\Support\Facades\Route;

class ApprovalController extends Controller
{

    private function getStatus($status) {
        if ($status == "approved") {
            return 1;
        }

        if ($status == "unapproved") {
            return 0;
        }

        return -1;
    }

    private function getRedirectRoute($string) {
        $parts = explode('.', $string);
        if (count($parts) > 1) {
            array_shift($parts);
            return implode('.', $parts);
        } else {
            return $string;
        }
    }

    private function getType($type) {
        if ($type == "icons") {
            return 2;
        }

        return 0;
    }

    public function approvals($type, $status = "")
    {

        if (!in_array($type, array('images', 'icons'))) {
            return redirect()->route('approvals', 'images');
        }

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
        $tag = null;


        //Tags
        if(!empty($tag)){
            $images = $images->where('tags', 'like', '%'.$tag.'%');
        }

        //Type
        switch($this->getType($type)){
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
            $paginate = 48;
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
                $images_count = $images_count->Where('thumbnail', 1);
            } else {
                $images = $images->where('thumbnail', 1)->where('category', $category);
                $images_count = $images_count->where('thumbnail', 1)->where('category', $category);
            }
            $paginate = 120;
            break;
        }

        //Status
        if ($status !== "") {
            // $images = $images->whereNull('approval');
            $image_status = $this->getStatus($status);
            if ($image_status > -1) {
                $images = $images->where('approval', $image_status);
            }
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

        return view('pages.approval', compact('images', 'counts', 'count_approval', 'type', 'status'));
    }

    public function approve($id)
    {
        $updateApprove = Image::where('id', $id)->update(['approval' => 1]);

    }

    public function unapprove($id)
    {
        $updateUnapprove = Image::where('id', $id)->update(['approval' => 0]);

    }

    public function detail($type, $id, $status = '')
    {
        if (empty($id) || empty($type) || ($status !== '' && empty($status)) || !in_array($type, array('images', 'icons'))) {
            return redirect()->route('approvals', 'images');
        }

        $group = 1;

        $image_status = $this->getStatus($status);

        $category_id = Auth::user()->category;
        $category = Category::select('category')->where('id', $category_id)->pluck('category')->first();

        $images_query = Image::query()
                    ->where('method', 1)
                    ->where('active', 1)
                    ->where('category', $category)
                    ->where('gallery', $group)
                    ->with('user');

        if ($type == 'images') {
            $images_query = $images_query
                            ->where('width', '>', 200)
                            ->where('height', '>', 200)
                            ->where(function ($query) {
                                $query->where('thumbnail', 0)->orWhereNull('thumbnail');
                            });
            $page_size = 48;
        } else {
            $images_query = $images_query->where('thumbnail', 1);
            $page_size = 120;
        }

        if ($id) {
            $image_query = clone $images_query;
            $image = $image_query->where('id', $id)->first();
        }

        if (!$image) {
            return redirect()->route('approvals', array($type, $status));
        }

        $total_query = clone $images_query;
        $images_query = clone $images_query->orderByDesc('id');

        if ($image_status > -1) {
            $total = $total_query->where('approval', $image_status)->count();
            $images = $images_query->where('approval', $image_status)->get();
        } else {
            $total = $total_query->count();
            $images = $images_query->get();
        }


        $ids = $images->pluck('id')->all();
        $index = array_search($id, $ids);

        $prev_image = ($index > 0) ? $images[$index - 1] : null;
        $next_image = ($index < count($ids) - 1) ? $images[$index + 1] : null;

        $index = $index + 1;
        $prev_image_page = ceil(($index > 0 ? $index - 1 : $index) / $page_size);
        $next_image_page = ceil(($index < count($ids) - 1 ? $index + 1 : $index) / $page_size);
        $page = ceil($index / $page_size);

        return view(
            'pages.detail',
            compact(
                'image',
                'total',
                'prev_image',
                'next_image',
                'type',
                'status',
                'index',
                'next_image_page',
                'prev_image_page',
                'page'
            )
        );
    }

    public function redirect()
    {
        $queryString = request()->getQueryString();
        $routeParams = Route::current()->parameters();
        $newRoute = $this->getRedirectRoute(Route::currentRouteName());

        array_unshift($routeParams, "image");

        return redirect()->to(route($newRoute, $routeParams) . ($queryString ? '?' . $queryString : ''));
    }
}
