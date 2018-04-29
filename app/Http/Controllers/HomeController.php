<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Category;
use App\Contact_query;
use App\Country;
use App\Post;
use App\Slider;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class HomeController extends Controller
{

    /**
     * @return mixed
     *
     * Installation view
     */
    public function installation(){
         echo "<p>".base_path('.env')."</p>";
        if ( file_exists(base_path('.env')) ) {
            return redirect(route('home'));
        }

        return view('theme/installation');
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * Installation post
     */
    public function installationPost(Request $request){

        if ( file_exists(base_path('.env')) ) {
            return redirect(route('home'));
        }
        
        $rules = [
            'hostname' => 'required',
            'dbport' => 'required',
            'username' => 'required',
            'password' => 'required',
            'database_name' => 'required',
            'envato_purchase_code' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()){
            $eror_msg = '';
            foreach ($validation->errors()->toArray() as $key => $value){
                $eror_msg .= "<p>{$value[0]}</p>";
            }
            return ['success' => 0, 'msg' => $eror_msg];
        }

        // $verify_envato_license = file_get_contents('https://themeqx.com/?envato_purchase_code='.$request->envato_purchase_code);
        // $verify_envato_license = \GuzzleHttp\json_decode($verify_envato_license);
        // if($verify_envato_license->success == 0){
        //     return ['success' => 0, 'msg' => trans('app.envato_purchase_code_invalid')];
        // }

        try{
            $mysqli_link = mysqli_connect($request->hostname, $request->username, $request->password, $request->database_name);

            // Name of the file
            $database = base_path('database-backup/classified_laravel_5_3.sql');
            // Temporary variable, used to store current query
            $templine = '';
            // Read in entire file
            $lines = file($database);
            // Loop through each line
            foreach ($lines as $line) {
                // Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                    continue;
                // Add this line to the current segment
                $templine .= $line;
                // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';') {
                    // Perform the query
                    mysqli_query($mysqli_link, $templine);
                    // Reset temp variable to empty
                    $templine = '';
                }
            }

        } catch (\Exception $e) {
            $db_error = '';
            $db_error .= "<p>Error: Unable to connect to Database. </p>";
            $db_error .= "<p>Debugging errno: " . mysqli_connect_errno() . "</p>";
            $db_error .= "<p>Debugging error: " . mysqli_connect_error() . "</p>";
            return ['success' => 0, 'msg' => $db_error];
        }
        mysqli_close($mysqli_link);


        $home_url = url('/');

        $conf = "";
        $conf .= "APP_ENV=local\n";
        $conf .= "APP_DEBUG=false\n";
        $conf .= "APP_KEY=base64:fpVldiLoOG+L562vfMat8+vPmxUzvVJOjXRd4wgMA/A=\n";
        $conf .= "APP_URL={$home_url}\n\n";

        $conf .= "DB_CONNECTION=mysql\n";
        $conf .= "DB_HOST={$request->hostname}\n";
        $conf .= "DB_PORT={$request->dbport} \n";
        $conf .= "DB_DATABASE={$request->database_name}\n";
        $conf .= "DB_USERNAME={$request->username}\n";
        $conf .= "DB_PASSWORD={$request->password}\n\n";
        //$conf .= "ENVATO_PURCHASE_CODE={$request->envato_purchase_code}\n\n";

        $conf .= "CACHE_DRIVER=file\n";
        $conf .= "SESSION_DRIVER=database\n";
        $conf .= "QUEUE_DRIVER=sync \n\n";

        $conf .= "REDIS_HOST=127.0.0.1\n";
        $conf .= "REDIS_PASSWORD=null\n";
        $conf .= "REDIS_PORT=6379\n\n";

        $conf .= "MAIL_DRIVER=smtp\n";
        $conf .= "MAIL_HOST=mailtrap.io\n";
        $conf .= "MAIL_PORT=2525\n";
        $conf .= "MAIL_USERNAME=null\n";
        $conf .= "MAIL_PASSWORD=null\n";
        $conf .= "MAIL_ENCRYPTION=null\n";

        $unable_to_open = "Unable to open file! <br /> please create a <b>.env</b> file manually in your document root with below content (this is configuration) <br /><br />";

        $unable_to_open .= "<pre>{$conf}</pre>";
        try{
        $open_env_file = fopen(base_path(".env"), "w") or die($unable_to_open);
        fwrite($open_env_file, $conf);
        fclose($open_env_file);
        chmod(base_path(".env"), 0777);
        }catch (\Exception $ex) {
            return ['success' => 0, 'msg' => $unable_to_open];
        }

        //Return to home
        //return redirect(route('home'));
        return ['success' => 1, 'msg' => '.env file has been created'];
    }

    public function index(){

        /**
         * For installation
         */
        if ( ! file_exists(base_path('.env')) ) {
          
            return redirect(route('installation'));
        }

        $limit_premium_ads = get_option('number_of_premium_ads_in_home');
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $limit_urgent_ads = get_option('number_of_urgent_ads_in_home');

        $sliders = Slider::all();
        $countries = Country::all();
        $top_categories = Category::whereCategoryId(0)->orderBy('category_name', 'asc')->get();
        $premium_ads = Ad::activePremium()->with('category', 'city')->limit($limit_premium_ads)->orderBy('id', 'desc')->get();
        $regular_ads = Ad::activeRegular()->with('category', 'city')->limit($limit_regular_ads)->orderBy('id', 'desc')->get();
        $urgent_ads = Ad::activeUrgent()->with('category', 'city')->limit($limit_urgent_ads)->orderBy('id', 'desc')->get();

        $posts = Post::whereType('post')->whereStatus('1')->limit(get_option('blog_post_amount_in_homepage'))->get();

        return view('theme.index', compact('top_categories', 'premium_ads', 'regular_ads','urgent_ads', 'countries', 'sliders', 'posts'));
    }

    public function contactUs(){
        $title = trans('app.contact_us');
        return view('theme.contact_us', compact('title'));
    }

    public function contactUsPost(Request $request){
        $rules = [
            'name'  => 'required',
            'email'  => 'required|email',
            'message'  => 'required',
        ];
        $this->validate($request, $rules);
        Contact_query::create(array_only($request->input(), ['name', 'email', 'message']));
        return redirect()->back()->with('success', trans('app.your_message_has_been_sent'));
    }

    public function contactMessages(){
        $title = trans('app.contact_messages');
        return view('admin.contact_messages', compact('title'));
    }

    public function contactMessagesData(){
        $contact_messages = Contact_query::select('name', 'email', 'message','created_at')->orderBy('id', 'desc')->get();

        return  Datatables::of($contact_messages)
            ->editColumn('created_at',function($contact_message){
                return $contact_message->created_at_datetime();
            })
            ->make();
    }

    /**
     * Switch Language
     */
    public function switchLang($lang){
        session(['lang'=>$lang]);
        //return redirect(route('home'));
        return back();
    }

    /**
     * Reset Database
     */
    public function resetDatabase(){
        $database_location = base_path("database-backup/classified.sql");
        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($database_location);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';')
            {
                // Perform the query
                DB::statement($templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
        $now_time = date("Y-m-d H:m:s");
        DB::table('ads')->update(['created_at' => $now_time, 'updated_at' => $now_time]);
    }


}
