<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;

use App\Http\Requests;

class LanguageController extends Controller
{
    public function index(){
        $title = trans('app.language_settings');
        $languages = Language::all();

        return view('admin.languages', compact('title', 'languages'));
    }

    public function store(Request $request){
        $rules = [
            'language_name' => 'required',
            'language_code' => 'required|max:5',
        ];
        $this->validate($request, $rules);

        $language_exist = Language::where('language_name',$request->language_name)->orWhere('language_code',$request->language_code)->first();
        if ($language_exist){
            return back()->with('error', trans('app.language_exists_msg'));
        }
        
        $inputs = array_except($request->input(), '_token');
        $language_created = Language::create($inputs);
        if ($language_created){
            return back()->with('success', trans('app.language_created'));
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->data_id;

        $delete = Language::where('id', $id)->delete();
        if ($delete){
            return ['success' => 1, 'msg' => trans('app.language_deleted_success')];
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];

    }
    

}
