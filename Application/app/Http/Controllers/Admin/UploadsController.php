<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Storage;

class UploadsController extends Controller
{

    // View uploadss
    public function index(Request $request)
    {
        // on search
        if ($request->input('q')) {
            $q = $request->input('q');
            $uploads = Image::where('image_id', 'LIKE', '%' . $q . '%')
                ->orWhere('id', 'like', '%' . $q . '%')
                ->orderbyDesc('id')
                ->get();
        } else {
            // uploads data
            $uploads = Image::orderbyDesc('id')->with('user')->paginate(15);
        }

        return view('admin.uploads', ['uploads' => $uploads]);
    }

    // Delete image
    public function deleteImage($id)
    {
        // get image by id
        $avdata = Image::where('id', $id)->first();
        // if data not null
        if ($avdata != null) {
            // Check if image upload on server or on amazon
            if ($avdata->method == 1) {
                $image = str_replace(url('/') . '/', '', $avdata->image_path);
                if (file_exists($image)) {
                    $deleteImage = File::delete($image);
                }
            } elseif ($avdata->method == 2) {
                // delete image from amazon s3
                $image = pathinfo(storage_path() . $avdata->image_path, PATHINFO_EXTENSION);
                $awsImage = $avdata->image_id . '.' . $image; // file name on amazon s3
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
            $delete = Image::where('id', $id)->delete();
            // if delete
            if ($delete) {
                // Success response
                return response()->json([
                    'success' => 'Image deleted successfully',
                ]);
            } else {
                // Error response
                return response()->json(['error' => 'Delete error please refresh page and try again']);
            }
        } else {
            // Error response if data is null
            return response()->json(['error' => 'Delete error please refresh page and try again']);
        }
    }

    // View image
    public function viewImage($id)
    {
        // Get image data
        $image = Image::where('id', $id)->with('user')->first();
        // if data not null
        if ($image != null) {
            // Retrun to view with image data
            return view('admin.view.image', ['image' => $image]);
        } else {
            // Abort 404 if data is null
            abort(404);
        }

    }
}
