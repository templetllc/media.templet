<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Preset;
use Auth;

class PresetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // get all presets data
        $presets = Preset::all();
        return view('admin.presets', ['presets' => $presets]);
    }

    // Edit preset
    public function editPreset($id)
    {
        // get user using id
        $preset = Preset::find($id);
        // if not data null
        if ($preset != null) {
            // Return preset data
            return view('admin.edit.preset', ['preset' => $preset]);
        } else {
            // back to users f data null
            return redirect('admin/presets');
        }
    }   

    // Add new preset
    public function addPreset(Request $request)
    {
        // validate form
        $validator = Validator::make($request->all(), [
            'preset' => ['required', 'string', 'max:80'],
            'width' => ['required', 'numeric'],
            'height' => ['required', 'numeric'],
        ]);

        // error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $active = $request['active'] ? 1 : 0;
        $value = $request['width']."x".$request['height'];

        // create the new user
        $register = Preset::create([
            'preset'    => $request['preset'],
            'value'     => $value,
            'width'     => $request['width'],
            'height'    => $request['height'],
            'content'   => $request['content'],
            'active'    => $active,
        ]);

        // if registered
        if ($register) {
            // success response
            return response()->json([
                'success' => 'New preset added successfully',
            ]);
        }
    }

    // update preset info
    public function editPresetStore(Request $request)
    {
        // Get preset data
        $preset = Preset::where('id', $request['preset_id'])->first();
        // If preset data is null
        if ($preset != null) {
            // Validate null
            $validator = null;
            
            $validator = Validator::make($request->all(), [
                'preset' => ['required', 'string', 'max:80'],
                'width' => ['required', 'numeric'],
                'height' => ['required', 'numeric'],
            ]);

            // Errors response
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()]);
            }

            $active = $request['active'] ? 1 : 0;
            $value = $request['width']."x".$request['height'];

            // update preset
            $presetUpdate = Preset::where('id', $request['preset_id'])->update([
                'preset'    => $request['preset'],
                'value'     => $value,
                'width'     => $request['width'],
                'height'    => $request['height'],
                'content'   => $request['content'],
                'active'    => $active,
            ]);

            if ($presetUpdate) {
                // Success response
                return response()->json([
                    'success' => 'updated successfully',
                ]);
            } else {
                // Error response
                return response()->json([
                    'error' => 'Error please refresh preset and try again',
                ]);
            }
        } else {
            // Error response
            return response()->json([
                'error' => 'illegal request',
            ]);
        }
    }

    // Delete preset
    public function deletepreset($id)
    {
        // get preset by id
        $preset = preset::where('id', $id)->first();
        // if data not null
        if ($preset != null) {
            // Delete preset
            $delete = preset::where('id', $id)->delete();
            // if delete
            if ($delete) {
                // Success response
                return response()->json([
                    'success' => 'preset deleted successfully',
                ]);
            } else {
                // Error response
                return response()->json(['error' => 'Delete error please refresh preset and try again']);
            }
        } else {
            // Error response if data is null
            return response()->json(['error' => 'Delete error please refresh preset and try again']);
        }
    }
}
