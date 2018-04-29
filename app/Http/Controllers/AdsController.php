<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Brand;
use App\Category;
use App\City;
use App\Country;
use App\Media;
use App\Payment;
use App\Report_ad;
use App\State;
use App\Sub_Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('app.all_ads');
        $ads = Ad::with('city', 'country', 'state')->whereStatus('1')->orderBy('id', 'desc')->paginate(20);

        return view('admin.all_ads', compact('title', 'ads'));
    }

    public function adminPendingAds()
    {
        $title = trans('app.pending_ads');
        $ads = Ad::with('city', 'country', 'state')->whereStatus('0')->orderBy('id', 'desc')->paginate(20);

        return view('admin.all_ads', compact('title', 'ads'));
    }
    public function adminBlockedAds()
    {
        $title = trans('app.blocked_ads');
        $ads = Ad::with('city', 'country', 'state')->whereStatus('2')->orderBy('id', 'desc')->paginate(20);

        return view('admin.all_ads', compact('title', 'ads'));
    }
    
    public function myAds(){
        $title = trans('app.my_ads');

        $user = Auth::user();
        $ads = $user->ads()->with('city', 'country', 'state')->orderBy('id', 'desc')->paginate(20);
        
        return view('admin.my_ads', compact('title', 'ads'));
    }

    public function pendingAds(){
        $title = trans('app.my_ads');

        $user = Auth::user();
        $ads = $user->ads()->whereStatus('0')->with('city', 'country', 'state')->orderBy('id', 'desc')->paginate(20);

        return view('admin.pending_ads', compact('title', 'ads'));
    }

    public function favoriteAds(){
        $title = trans('app.favourite_ads');

        $user = Auth::user();
        $ads = $user->favourite_ads()->with('city', 'country', 'state')->orderBy('id', 'desc')->paginate(20);
        
        return view('admin.favourite_ads', compact('title', 'ads'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Auth::user()->id;
        $title = trans('app.post_an_ad');
        $categories = Category::where('category_id', 0)->get();
        $countries = Country::all();
        $ads_images = Media::whereUserId($user_id)->whereAdId(0)->whereRef('ad')->get();
        
        $previous_brands = Brand::where('category_id', old('category'))->get();
        $previous_states = State::where('country_id', old('country'))->get();
        $previous_cities = City::where('state_id', old('state'))->get();

        return view('admin.create_ad', compact('title', 'categories', 'countries', 'ads_images', 'previous_brands', 'previous_states', 'previous_cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $ads_price_plan = get_option('ads_price_plan');


        $rules = [
            'category'  => 'required',
            'ad_title'  => 'required',
            'ad_description'  => 'required',
            'type'  => 'required',
            'condition'  => 'required',
            'country'  => 'required',
            'seller_name'  => 'required',
            'seller_email'  => 'required',
            'seller_phone'  => 'required',
            'address'  => 'required',
        ];

        if( $ads_price_plan != 'all_ads_free'){
            $rules['price_plan'] = 'required';
        }

        $this->validate($request, $rules);

        $title = $request->ad_title;
        $slug = unique_slug($title);
       

        $sub_category = Category::find($request->category);

        $is_negotialble = $request->negotiable ? $request->negotiable : 0;
        $brand_id = $request->brand ? $request->brand : 0;
        $mark_ad_urgent = $request->mark_ad_urgent ? $request->mark_ad_urgent : 0;
        $video_url = $request->video_url ? $request->video_url : '';

        $data = [
            'title' => $request->ad_title,
            'slug' => $slug,
            'description' => $request->ad_description,
            'category_id' => $sub_category->category_id,
            'sub_category_id' => $request->category,
            'brand_id' => $brand_id,
            'type' => $request->type,
            'ad_condition' => $request->condition,
            'price' => $request->price,
            'is_negotiable' => $is_negotialble,

            'seller_name' => $request->seller_name,
            'seller_email' => $request->seller_email,
            'seller_phone' => $request->seller_phone,
            'country_id' => $request->country,
            'state_id' => $request->state,
            'city_id' => $request->city,
            'address' => $request->address,
            'video_url' => $video_url,
            'price_plan' => $request->price_plan,
            'mark_ad_urgent' => $mark_ad_urgent,
            'status' => '0',
            'user_id' => $user_id,
        ];

        //Check ads moderation settings
        if (get_option('ads_moderation') == 'direct_publish'){
            $data['status'] = '1';
        }

        //if price_plan not in post data, then set a default value, although mysql will save it as enum first value
        if ( ! $request->price_plan){
            $data['price_plan'] = 'regular';
        }

        $created_ad = Ad::create($data);

        /**
         * iF add created
         */
        if ($created_ad){
            //Attach all unused media with this ad
            Media::whereUserId($user_id)->whereAdId(0)->whereRef('ad')->update(['ad_id'=>$created_ad->id]);

            /**
             * Payment transaction login here
             */
            $ads_price = get_ads_price($created_ad->price_plan);
            if ($mark_ad_urgent){
                $ads_price = $ads_price + get_option('urgent_ads_price');
            }

            if ($ads_price){
                //Insert checkout Logic

                $transaction_id = 'tran_'.time().str_random(6);
                // get unique recharge transaction id
                while( ( Payment::whereLocalTransactionId($transaction_id)->count() ) > 0) {
                    $transaction_id = 'reid'.time().str_random(5);
                }
                $transaction_id = strtoupper($transaction_id);

                $currency = get_option('currency_sign');
                $payments_data = [
                    'ad_id'     => $created_ad->id,
                    'user_id'   => $user_id,
                    'amount'    => $ads_price,
                    'payment_method'    => $request->payment_method,
                    'status'    => 'initial',
                    'currency'  => $currency,
                    'local_transaction_id'  => $transaction_id
                ];
                $created_payment = Payment::create($payments_data);

                return redirect(route('payment_checkout', $created_payment->local_transaction_id));
            }

            return redirect(route('pending_ads'))->with('success', trans('app.ad_created_msg'));

        }
        

        //dd($request->input());
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
        $user = Auth::user();
        $user_id = $user->id;

        $title = trans('app.edit_ad');
        $ad = Ad::find($id);

        if (!$ad)
            return view('admin.error.error_404');

        if (! $user->is_admin()){
            if ($ad->user_id != $user_id){
                return view('admin.error.error_404');
            }
        }
        
        $categories = Category::where('category_id', 0)->get();
        $countries = Country::all();
        $ads_images = Media::whereUserId($user_id)->whereAdId(0)->whereRef('ad')->get();

        $previous_brands = Brand::where('category_id', $ad->sub_category_id)->get();
        $previous_states = State::where('country_id', $ad->country_id)->get();
        $previous_cities = City::where('state_id', $ad->state_id)->get();
        
        return view('admin.edit_ad', compact('title', 'categories', 'countries', 'ads_images', 'ad', 'previous_brands', 'previous_states', 'previous_cities'));

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
        $ad = Ad::find($id);
        $user = Auth::user();
        $user_id = $user->id;

        if (! $user->is_admin()){
            if ($ad->user_id != $user_id){
                return view('admin.error.error_404');
            }
        }
        $mark_ad_urgent = $request->mark_ad_urgent ? $request->mark_ad_urgent : '0';

        $rules = [
            'category'  => 'required',
            'ad_title'  => 'required',
            'ad_description'  => 'required',
            'type'  => 'required',
            'condition'  => 'required',
            'country'  => 'required',
            'seller_name'  => 'required',
            'seller_email'  => 'required',
            'seller_phone'  => 'required',
            'address'  => 'required',
        ];

        $this->validate($request, $rules);

        $title = $request->ad_title;
        //$slug = unique_slug($title);
        
        $sub_category = Category::find($request->category);
        $is_negotialble = $request->negotiable ? $request->negotiable : '0';
        $brand_id = $request->brand ? $request->brand : 0;
        $video_url = $request->video_url ? $request->video_url : '';

        $data = [
            'title' => $request->ad_title,
            'description' => $request->ad_description,
            'category_id' => $sub_category->category_id,
            'sub_category_id' => $request->category,
            'brand_id' => $brand_id,
            'type' => $request->type,
            'ad_condition' => $request->condition,
            'price' => $request->price,
            'is_negotiable' => $is_negotialble,

            'seller_name' => $request->seller_name,
            'seller_email' => $request->seller_email,
            'seller_phone' => $request->seller_phone,
            'country_id' => $request->country,
            'state_id' => $request->state,
            'city_id' => $request->city,
            'address' => $request->address,
            'video_url' => $video_url,
            'price_plan' => $request->price_plan,
            'mark_ad_urgent' => $mark_ad_urgent,

        ];
        
        $updated_ad = $ad->update($data);

        /**
         * iF add created
         */
        if ($updated_ad){
            //Attach all unused media with this ad
            Media::whereUserId($user_id)->whereAdId(0)->whereRef('ad')->update(['ad_id'=>$ad->id]);
        }

        return redirect()->back()->with('success', trans('app.ad_updated'));
    }


    public function adStatusChange(Request $request){
        $slug = $request->slug;
        $ad = Ad::whereSlug($slug)->first();
        if ($ad){
            $value = $request->value;
            /*
            $ad->status = $value;
            $ad->save();*/
            ad_status_change($ad->id, $value);
            if ($value ==1){
                return ['success'=>1, 'msg' => trans('app.ad_approved_msg')];
            }elseif($value ==2){
                return ['success'=>1, 'msg' => trans('app.ad_blocked_msg')];
            }
            elseif($value ==3){
                return ['success'=>1, 'msg' => trans('app.ad_archived_msg')];
            }
        }
        return ['success'=>0, 'msg' => trans('app.error_msg')];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $slug = $request->slug;
        $ad = Ad::whereSlug($slug)->first();
        if ($ad){
            $media = Media::whereAdId($ad->id)->get();
            if ($media->count() > 0){
                foreach($media as $m){
                    $storage = Storage::disk($m->storage);
                    if ($storage->has('uploads/images/'.$m->media_name)){
                        $storage->delete('uploads/images/'.$m->media_name);
                    }
                    if ($m->type == 'image'){
                        if ($storage->has('uploads/images/thumbs/'.$m->media_name)){
                            $storage->delete('uploads/images/thumbs/'.$m->media_name);
                        }
                    }
                    $m->delete();
                }
            }
            $ad->delete();
            return ['success'=>1, 'msg' => trans('app.media_deleted_msg')];
        }
        return ['success'=>0, 'msg' => trans('app.error_msg')];
    }

    public function getSubCategoryByCategory(Request $request){
        $category_id = $request->category_id;
        $brands = Sub_Category::whereCategoryId($category_id)->select('id', 'category_name', 'category_slug')->get();
        return $brands;
    }

    public function getBrandByCategory(Request $request){
        $category_id = $request->category_id;
        $brands = Brand::whereCategoryId($category_id)->select('id', 'brand_name')->get();
        return $brands;
    }

    public function getStateByCountry(Request $request){
        $country_id = $request->country_id;
        $states = State::whereCountryId($country_id)->select('id', 'state_name')->get();
        return $states;
    }

    public function getCityByState(Request $request){
        $state_id = $request->state_id;
        $cities = City::whereStateId($state_id)->select('id', 'city_name')->get();
        return $cities;
    }

    public function getParentCategoryInfo(Request $request){
        $category_id = $request->category_id;
        $sub_category = Category::find($category_id);
        $category = Category::find($sub_category->category_id);
        return $category;
    }

    public function uploadAdsImage(Request $request){
        $user_id = Auth::user()->id;

        if ($request->hasFile('images')){
            $image = $request->file('images');
            $valid_extensions = ['jpg','jpeg','png'];

            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return ['success' => 0, 'msg' => implode(',', $valid_extensions).' '.trans('app.valid_extension_msg')];
            }

            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());

            $resized = Image::make($image)->resize(640, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $resized_thumb = Image::make($image)->resize(320, 213)->stream();

            $image_name = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $imageFileName = 'uploads/images/'.$image_name;
            $imageThumbName = 'uploads/images/thumbs/'.$image_name;

            try{
                //Upload original image
                $is_uploaded = current_disk()->put($imageFileName, $resized->__toString(), 'public');

                if ($is_uploaded) {
                    //Save image name into db
                    $created_img_db = Media::create(['user_id' => $user_id, 'media_name'=>$image_name, 'type'=>'image', 'storage' => get_option('default_storage'), 'ref'=>'ad']);

                    //upload thumb image
                    current_disk()->put($imageThumbName, $resized_thumb->__toString(), 'public');
                    $img_url = media_url($created_img_db, false);
                    return ['success' => 1, 'img_url' => $img_url];
                } else {
                    return ['success' => 0];
                }
            } catch (\Exception $e){
                return $e->getMessage();
            }

        }
    }
    /**
     * @param Request $request
     * @return array
     */

    public function deleteMedia(Request $request){
        $media_id = $request->media_id;
        $media = Media::find($media_id);

        $storage = Storage::disk($media->storage);
        if ($storage->has('uploads/images/'.$media->media_name)){
            $storage->delete('uploads/images/'.$media->media_name);
        }

        if ($media->type == 'image'){
            if ($storage->has('uploads/images/thumbs/'.$media->media_name)){
                $storage->delete('uploads/images/thumbs/'.$media->media_name);
            }
        }

        $media->delete();
        return ['success'=>1, 'msg'=>trans('app.media_deleted_msg')];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function featureMediaCreatingAds(Request $request){
        $user_id = Auth::user()->id;
        $media_id = $request->media_id;

        Media::whereUserId($user_id)->whereAdId(0)->whereRef('ad')->update(['is_feature'=>'0']);
        Media::whereId($media_id)->update(['is_feature'=>'1']);

        return ['success'=>1, 'msg'=>trans('app.media_featured_msg')];
    }

    /**
     * @return mixed
     */
    
    public function appendMediaImage(){
        $user_id = Auth::user()->id;
        $ads_images = Media::whereUserId($user_id)->whereAdId(0)->whereRef('ad')->get();

        return view('admin.append_media', compact('ads_images'));
    }

    /**
     * Listing
     */

    public function listing(Request $request){
        $ads = Ad::active();
        $business_ads_count = Ad::active()->business();
        $personal_ads_count = Ad::active()->personal();

        $premium_ads = Ad::activePremium();

        if ($request->q){
            $ads = $ads->where(function($ads) use($request){
                $ads->where('title','like', "%{$request->q}%")->orWhere('description','like', "%{$request->q}%");
            });
            
            $business_ads_count = $business_ads_count->where(function($business_ads_count) use($request){
                $business_ads_count->where('title','like', "%{$request->q}%")->orWhere('description','like', "%{$request->q}%");
            });

            $personal_ads_count = $personal_ads_count->where(function($personal_ads_count) use($request){
                $personal_ads_count->where('title','like', "%{$request->q}%")->orWhere('description','like', "%{$request->q}%");
            });
        }
        if ($request->category){
            $ads = $ads->whereCategoryId($request->category);
            $business_ads_count = $business_ads_count->whereCategoryId($request->category);
            $personal_ads_count = $personal_ads_count->whereCategoryId($request->category);

            $premium_ads = $premium_ads->whereCategoryId($request->category);
        }
        if ($request->sub_category){
            $ads = $ads->whereSubCategoryId($request->sub_category);
            $business_ads_count = $business_ads_count->whereSubCategoryId($request->sub_category);
            $personal_ads_count = $personal_ads_count->whereSubCategoryId($request->sub_category);

            $premium_ads = $premium_ads->whereSubCategoryId($request->sub_category);
        }
        if ($request->brand){
            $ads = $ads->whereBrandId($request->brand);
            $business_ads_count = $business_ads_count->whereBrandId($request->brand);
            $personal_ads_count = $personal_ads_count->whereBrandId($request->brand);
        }
        if ($request->condition){
            $ads = $ads->whereAdCondition($request->condition);
            $business_ads_count = $business_ads_count->whereAdCondition($request->condition);
            $personal_ads_count = $personal_ads_count->whereAdCondition($request->condition);
        }
        if ($request->type){
            $ads = $ads->whereType($request->type);
            $business_ads_count = $business_ads_count->whereType($request->type);
            $personal_ads_count = $personal_ads_count->whereType($request->type);
        }
        if ($request->country){
            $ads = $ads->whereCountryId($request->country);
            $business_ads_count = $business_ads_count->whereCountryId($request->country);
            $personal_ads_count = $personal_ads_count->whereCountryId($request->country);
        }
        if ($request->state){
            $ads = $ads->whereStateId($request->state);
            $business_ads_count = $business_ads_count->whereStateId($request->state);
            $personal_ads_count = $personal_ads_count->whereStateId($request->state);
        }
        if ($request->city){
            $ads = $ads->whereCityId($request->city);
            $business_ads_count = $business_ads_count->whereCityId($request->city);
            $personal_ads_count = $personal_ads_count->whereCityId($request->city);
        }
        if ($request->min_price){
            $ads = $ads->where('price', '>=', $request->min_price);
            $business_ads_count = $business_ads_count->where('price', '>=', $request->min_price);
            $personal_ads_count = $personal_ads_count->where('price', '>=', $request->min_price);
        }
        if ($request->max_price){
            $ads = $ads->where('price', '<=', $request->max_price);
            $business_ads_count = $business_ads_count->where('price', '<=', $request->max_price);
            $personal_ads_count = $personal_ads_count->where('price', '<=', $request->max_price);
        }
        if ($request->adType){
            if ($request->adType == 'business') {
                $ads = $ads->business();
            }elseif ($request->adType == 'personal'){
                $ads = $ads->personal();
            }
        }
        if ($request->user_id){
            $ads = $ads->whereUserId($request->user_id);
            $business_ads_count = $business_ads_count->whereUserId($request->user_id);
            $personal_ads_count = $personal_ads_count->whereUserId($request->user_id);
        }
        if ($request->shortBy){
            switch ($request->shortBy){
                case 'price_high_to_low':
                    $ads = $ads->orderBy('price', 'desc');
                    break;
                case 'price_low_to_height':
                    $ads = $ads->orderBy('price', 'asc');
                    break;
                case 'latest':
                    $ads = $ads->orderBy('id', 'desc');
                    break;
            }
        }else{
            $ads = $ads->orderBy('id', 'desc');
        }


        $ads_per_page = get_option('ads_per_page');
        $ads = $ads->with('feature_img', 'country', 'state', 'city', 'category');
        $ads = $ads->paginate($ads_per_page);


        //Check max impressions
        $max_impressions = get_option('premium_ads_max_impressions');
        $premium_ads = $premium_ads->where('max_impression', '<', $max_impressions);
        $take_premium_ads = get_option('number_of_premium_ads_in_listing');
        if ($take_premium_ads > 0){
            $premium_order_by = get_option('order_by_premium_ads_in_listing');
            $premium_ads = $premium_ads->take($take_premium_ads);
            $last_days_premium_ads = get_option('number_of_last_days_premium_ads');

            $premium_ads = $premium_ads->where('created_at', '>=', Carbon::now()->timezone(get_option('default_timezone'))->subDays($last_days_premium_ads));

            if ($premium_order_by == 'latest'){
                $premium_ads = $premium_ads->orderBy('id', 'desc');
            }elseif ($premium_order_by == 'random'){
                $premium_ads = $premium_ads->orderByRaw('RAND()');
            }

            $premium_ads = $premium_ads->get();

        }else{
            $premium_ads = false;
        }

        $business_ads_count = $business_ads_count->count();
        $personal_ads_count = $personal_ads_count->count();

        $title = trans('app.post_an_ad');
        $categories = Category::where('category_id', 0)->get();
        $countries = Country::all();

        $selected_categories = Category::find($request->category);
        $selected_sub_categories = Category::find($request->sub_category);

        $selected_countries = Country::find($request->country);
        $selected_states = State::find($request->state);
        //dd($selected_countries->states);

        return view($this->theme.'listing', compact('top_categories', 'ads', 'title', 'categories', 'countries', 'selected_categories', 'selected_sub_categories', 'selected_countries', 'selected_states', 'personal_ads_count', 'business_ads_count', 'premium_ads'));
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function singleAd($id, $slug){
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        //$ad = Ad::whereSlug($slug)->first();
        $ad = Ad::find($id);

        if (! $ad){
            return view('theme.error_404');
        }
        
        if ( ! $ad->is_published()){
            if (Auth::check()){
                $user_id = Auth::user()->id;
                if ($user_id != $ad->user_id){
                    return view('theme.error_404');
                }
            }else{
                return view('theme.error_404');
            }
        }else{
            $ad->view = $ad->view+1;
            $ad->save();
        }

        $title = $ad->title;

        //Get Related Ads, add [->whereCountryId($ad->country_id)] for more specific results
        $related_ads = Ad::active()->whereCategoryId($ad->category_id)->where('id', '!=',$ad->id)->with('category', 'city')->limit($limit_regular_ads)->orderByRaw('RAND()')->get();
        
        return view($this->theme.'single_ad', compact('ad', 'title', 'related_ads'));
    }
    
    public function switchGridListView(Request $request){
        session(['grid_list_view' => $request->grid_list_view]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function reportAds(Request $request){
        $ad = Ad::whereSlug($request->slug)->first();
        if ($ad) {
            $data = [
                'ad_id' => $ad->id,
                'reason' => $request->reason,
                'email' => $request->email,
                'message' => $request->message,
            ];
            Report_ad::create($data);
            return ['status'=>1, 'msg'=>trans('app.ad_reported_msg')];
        }
        return ['status'=>0, 'msg'=>trans('app.error_msg')];
    }
    
    
    public function reports(){
        $reports = Report_ad::orderBy('id', 'desc')->with('ad')->paginate(20);
        $title = trans('app.ad_reports');

        return view('admin.ad_reports', compact('title', 'reports'));
    }

    public function deleteReports(Request $request){
        Report_ad::find($request->id)->delete();
        return ['success'=>1, 'msg' => trans('app.report_deleted_success')];
    }
    
    public function reportsByAds($slug){
        $user = Auth::user();

        if ($user->is_admin()){
            $ad = Ad::whereSlug($slug)->first();
        }else{
            $ad = Ad::whereSlug($slug)->whereUserId($user->id)->first();
        }

        if (! $ad){
            return view('admin.error.error_404');
        }

        $reports = $ad->reports()->paginate(20);

        $title = trans('app.ad_reports');
        return view('admin.reports_by_ads', compact('title', 'ad', 'reports'));

    }


}
