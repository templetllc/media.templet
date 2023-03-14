<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::all();
        return view('admin.categories', ['categories' => $categories]);
    }

    // Add new category
    public function addCategory(Request $request)
    {
        // validate form
        $validator = Validator::make($request->all(), [
            'category' => ['required', 'string', 'max:80']
        ]);

        // error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $active = $request['active'] ? 1 : 0;
        $featured = $request['featured'] ? 1 : 0;

        // create the new user
        $register = Category::create([
            'category'  => $request['category'],
            'featured'  => $featured,
            'active'    => $active,
        ]);

        // if registered
        if ($register) {
            // success response
            return response()->json([
                'success' => 'New category added successfully',
            ]);
        }
    }

    // Edit preset
    public function editCategory($id)
    {
        // get user using id
        $category = Category::find($id);
        // if not data null
        if ($category != null) {
            // Return category data
            return view('admin.edit.category', ['category' => $category]);
        } else {
            // back to users f data null
            return redirect('admin/category');
        }
    }

    // update category info
    public function editCategoryStore(Request $request)
    {
        // Get category data
        $category = Category::where('id', $request['category_id'])->get();

        // If category data is null
        if ($category != null) {

            // Validate null
            $validator = null;
            
            $validator = Validator::make($request->all(), [
                'category' => ['required', 'string', 'max:80'],
            ]);

            // Errors response
            if ($validator->fails()) {
                return response()->json(['error 1' => $validator->errors()->all()]);
            }

            $active = $request['active'] ? 1 : 0;
            $featured = $request['featured'] ? 1 : 0;

            // update category
            $categoryUpdate = Category::where('id', $request['category_id'])->update([
                'category'  => $request['category'],
                'featured'  => $featured,
                'active'    => $active,
            ]);

            if ($categoryUpdate) {
                // Success response
                return response()->json([
                    'success' => 'updated successfully',
                ]);
            } else {
                // Error response
                return response()->json([
                    'error' => 'Error please refresh category and try again',
                ]);
            }
        } else {
            // Error response
            return response()->json([
                'error' => 'illegal request',
            ]);
        }
    }

    // Delete category
    public function deleteCategory($id)
    {
        // get category by id
        $category = Category::where('id', $id)->first();
        // if data not null
        if ($category != null) {
            // Delete category
            $delete = Category::where('id', $id)->delete();
            // if delete
            if ($delete) {
                // Success response
                return response()->json([
                    'success' => 'category deleted successfully',
                ]);
            } else {
                // Error response
                return response()->json(['error' => 'Delete error please refresh category and try again']);
            }
        } else {
            // Error response if data is null
            return response()->json(['error' => 'Delete error please refresh category and try again']);
        }
    } 
}
