<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use Illuminate\Http\Request;

use App\Http\Requests;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('app.brands');
        $brands = Brand::all();
        $categories = Category::where('category_id', 0)->get();


        return view('admin.brands', compact('title', 'brands', 'categories'));
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
            'brand_name' => 'required'
        ];
        $this->validate($request, $rules);

        $slug = str_slug($request->brand_name);
        $duplicate = Brand::where('brand_slug', $slug)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.brand_exists_in_db'));
        }

        Brand::create(['brand_name' => $request->brand_name, 'brand_slug' => $slug, 'category_id' => $request->category]);
        return back()->with('success', trans('app.brand_created'));
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
        $title = trans('app.edit_brand');
        $edit_brand = Brand::find($id);

        $categories = Category::where('category_id', 0)->get();

        if ( ! $edit_brand)
            return redirect(route('parent_categories'))->with('error', trans('app.request_url_not_found'));

        return view('admin.edit_brand', compact('title', 'categories', 'edit_brand'));
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
            'brand_name' => 'required'
        ];
        $this->validate($request, $rules);

        $slug = str_slug($request->brand_name);
        $duplicate = Brand::where('brand_slug', $slug)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.brand_exists_in_db'));
        }

        Brand::whereId($id)->update(['brand_name' => $request->brand_name, 'brand_slug' => $slug, 'category_id' => $request->category]);

        return redirect(route('admin_brands'))->with('success', trans('app.brand_updated'));

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

        $delete = Brand::whereId($id)->delete();
        if ($delete){
            return ['success' => 1, 'msg' => trans('app.brand_deleted_success')];
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];

    }
}
