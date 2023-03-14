<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Setting;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Storage;

class FilesController extends Controller
{
    //
    public function index(){
        return view('test');
    }

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
                        $newImg = $request->file('uploads');
                        $mime = $newImg->getMimeType();
                        switch($mime){ 
                            case 'image/jpeg': 
                                $newImg = imagecreatefromjpeg($newImg); 
                                break; 
                            case 'image/png': 
                                $newImg = imagecreatefrompng($newImg); 
                                break; 
                            case 'image/gif': 
                                $newImg = imagecreatefromgif($newImg); 
                                break; 
                            default: 
                                $newImg = imagecreatefromjpeg($newImg); 
                        } 

                        // move image to path
                        //$upload = $request->file('uploads')->move($path, $imageName);
                        $upload = $newImg->move($path, $imageName);
                        // file name
                        $filename = url($path) . '/' . $imageName;
                        // method server host
                        $method = 1;
                    }
                    // if image uploded
                    if ($upload) {
                        // if user auth get user id
                        if (Auth::user()) {$userID = Auth::user()->id;} else { $userID = null;}
                        // create new image data
                        $data = Image::create([
                            'user_id' => $userID,
                            'image_id' => $string,
                            'image_path' => $filename,
                            'image_size' => $fileSize,
                            'method' => $method,
                        ]);
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
                'errors' => 'Server error please refresh page and try again.',
            ));
        }
    }
}
