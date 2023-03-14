<?php

namespace App\Http\Controllers\Pages\User;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Auth;

class DashboardController extends Controller
{
    // view user dashboard
    public function index()
    {
        $authId = Auth::user()->id; // user id
        // get user images using id
        $images = Image::where('user_id', $authId)->limit(10)->orderbyDesc('id')->get();
        return view('pages.user.dashboard', ['images' => $images]);
    }

    // Charts get all days
    public function getAllDays()
    {
        $authId = Auth::user()->id; // user id
        $day_array = array();
        $images_dates = Image::where('user_id', $authId)->orderBy('created_at', 'ASC')->pluck('created_at');
        $images_dates = json_decode($images_dates);

        if (!empty($images_dates)) {
            foreach ($images_dates as $unformatted_date) {
                $date = new \DateTime($unformatted_date);
                $day_no = $date->format('Y-m-d');
                $day_name = $date->format('Y-m-d');
                $day_array[$day_no] = $day_name;
            }
        }
        return $day_array;
    }

    // get daily count images
    public function getDailyImageCount($day)
    {
        $authId = Auth::user()->id;
        $daily_image_count = Image::where('user_id', $authId)->whereDate('created_at', $day)->get()->count();
        return $daily_image_count;
    }

    // get daily images data
    public function getDailyImageData()
    {
        $daily_image_count_array = array();
        $day_array = $this->getAllDays();
        $day_name_array = array();
        if (!empty($day_array)) {
            foreach ($day_array as $day_no => $day_name) {
                $daily_image_count = $this->getDailyImageCount($day_no);
                array_push($daily_image_count_array, $daily_image_count);
                array_push($day_name_array, $day_name);
            }
        }
        $max_no = max($daily_image_count_array);
        $daily_image_data_array = array(
            'days' => $day_name_array,
            'image_count_data' => $daily_image_count_array,
        );
        return $daily_image_data_array;

    }
}
