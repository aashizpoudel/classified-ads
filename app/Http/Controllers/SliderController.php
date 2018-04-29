<?php

namespace App\Http\Controllers;

use App\Slider;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('app.sliders');
        $sliders = Slider::orderBy('short_by', 'asc')->get();
        return view('admin.sliders', compact('title', 'sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('app.create_slider');
        return view('admin.sliders_create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $form_data = $request->all();

        $validator = \Illuminate\Support\Facades\Validator::make($form_data, Slider::$rules, Slider::$messages);

        if ($validator->fails()) {

            return [
                'status' => 'error',
                'message' => $validator->messages()->first(),
            ];

        }

        $photo = $form_data['img'];


        $original_name_without_ext = str_replace('.'.$photo->getClientOriginalExtension(), '', $photo->getClientOriginalName());

        $filename_ext = strtolower(time().str_random(5).'-'.str_slug($original_name_without_ext)).'.' . $photo->getClientOriginalExtension();

        $manager = new ImageManager();
        $read_image = $manager->make( $photo );
        $image = $read_image->stream();


        $imageFileName = '/uploads/sliders/'.$filename_ext;

        //Upload original image
        $is_uploaded = current_disk()->put($imageFileName, $image->__toString(), 'public');

        if( !$image) {
            return [
                'status' => 'error',
                'message' => 'Server error while uploading',
            ];
        }

        $database_image = Slider::create(['media_name' => $filename_ext, 'storage'=>get_option('default_storage')]);

        return [
            'status'    => 'success',
            'url'       => slider_url($database_image),
            'width'     => $read_image->width(),
            'height'    => $read_image->height()
        ];
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $media_id = $request->media_id;
        $media = Slider::find($media_id);
        $media->caption = $request->caption;
        $media->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $media_id = $request->media_id;
        $media = Slider::find($media_id);

        $storage = Storage::disk($media->storage);
        if ($storage->has('uploads/sliders/'.$media->media_name)){
            $storage->delete('uploads/sliders/'.$media->media_name);
        }
        
        $media->delete();
        return ['success'=>1, 'msg'=>trans('app.media_deleted_msg')];
    }
    
    public function postCrop(Request $request){
        $form_data = $request->all();
        $image_url = $form_data['imgUrl'];

        // resized sizes
        $imgW = intval($form_data['imgW'] * 2);
        $imgH = intval($form_data['imgH'] * 2);
        // offsets
        $imgY1 = intval($form_data['imgY1'] * 2);
        $imgX1 = intval($form_data['imgX1'] * 2);
        // crop box
        $cropW = $form_data['width'];
        $cropH = $form_data['height'];
        // rotation angle
        $angle = $form_data['rotation'];

        $filename_array = explode('sliders/', $image_url);
        $imageFileName = '/uploads/sliders/'.$filename_array[1];

        $manager = new ImageManager();
        $image = $manager->make( $image_url );
        $image->resize($imgW, $imgH)->rotate(-$angle)->crop($cropW, $cropH, $imgX1, $imgY1)->stream();

        //Upload original image
        $is_uploaded = current_disk()->put($imageFileName, $image->__toString(), 'public');

        if( !$image) {
            return [
                'status' => 'error',
                'message' => 'Server error while uploading',
            ];
        }

        return [
            'status' => 'success',
            'url' => asset($imageFileName)
        ];
    }
    
}
