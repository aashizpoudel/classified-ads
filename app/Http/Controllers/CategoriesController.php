<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

use App\Http\Requests;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * parent categories
     */
    public function index()
    {
        $title = trans('app.categories');
        $categories = Category::where('category_id', 0)->get();

        return view('admin.categories', compact('title', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'category_name' => 'required'
        ];
        $this->validate($request, $rules);

        $slug = str_slug($request->category_name);
        $duplicate = Category::where('category_slug', $slug)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.category_exists_in_db'));
        }

        $data = [
            'category_name' => $request->category_name,
            'category_slug' => $slug,
            'category_id'   => $request->parent_category,
            'fa_icon'       => $request->fa_icon,
            'color_class'   => $request->color_class,
            'description'   => $request->description,
        ];

        Category::create($data);
        return back()->with('success', trans('app.category_created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = trans('app.edit_category');
        $edit_category = Category::find($id);

        $categories = Category::where('category_id', 0)->get();

        if ( ! $edit_category)
            return redirect(route('parent_categories'))->with('error', trans('app.request_url_not_found'));

        return view('admin.edit_category', compact('title', 'categories', 'edit_category'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'category_name' => 'required'
        ];
        $this->validate($request, $rules);

        $slug = str_slug($request->category_name);

        $duplicate = Category::where('category_slug', $slug)->where('id', '!=', $id)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.category_exists_in_db'));
        }

        $data = [
            'category_name' => $request->category_name,
            'category_slug' => $slug,
            'category_id'   => $request->parent_category,
            'fa_icon'       => $request->fa_icon,
            'color_class'   => $request->color_class,
            'description'   => $request->description,
        ];
        Category::where('id', $id)->update($data);

        return back()->with('success', trans('app.category_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->data_id;
        
        $delete = Category::where('id', $id)->delete();
        if ($delete){
            return ['success' => 1, 'msg' => trans('app.category_deleted_success')];
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];

    }
}
