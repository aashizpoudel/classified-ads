<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Payment;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class PaymentController extends Controller
{
    
    public function index(){
        $title = trans('app.payments');
        return view('admin.payments', compact('title'));
    }
    public function paymentsData(){
        $user = Auth::user();
        if ($user->is_admin()){
            $payments = Payment::select('id','ad_id', 'user_id', 'amount','payment_method', 'status','local_transaction_id', 'created_at')->with('ad', 'user')->get();
        }else{
            $payments = Payment::select('id','ad_id', 'user_id', 'amount','payment_method', 'status','local_transaction_id', 'created_at')->whereUserId($user->id)->with('ad', 'user')->get();
        }

        return  Datatables::of($payments)
            ->editColumn('ad_id', function($payment){
                if ($payment->ad)
                return '<a href="'.route('single_ad', [$payment->ad->id, $payment->ad->slug]).'" target="_blank">'.$payment->ad->title.'</a>';
            })
            ->editColumn('user_id', function($payment){
                return '<a href="'.route('user_info', $payment->user->id).'"  target="_blank"> '.$payment->user->name.'</a>';
            })
            ->editColumn('status', function($payment){
                return '<a href="'.route('payment_info', $payment->local_transaction_id).'"  target="_blank"> '.$payment->status.'</a>';
            })
            ->editColumn('created_at',function($user){
                return $user->created_at_datetime();
            })
            ->removeColumn('id', 'local_transaction_id')
            ->make();
    }
    
    public function paymentInfo($tran_id){
        $payment = Payment::where('local_transaction_id', $tran_id)->first() ;

        if (!$payment){
            return view('admin.error.error_404');
        }

        $title = trans('app.payment_info');
        return view('admin.payment_info', compact('title', 'payment'));

    }
    
    public function checkout($transaction_id){
        $payment = Payment::whereLocalTransactionId($transaction_id)->whereStatus('initial')->first();
        $title = trans('app.checkout');
        if ($payment){
            return view('admin.checkout', compact('title','payment'));
        }
        return view('admin.error.invalid_transaction', compact('title','payment'));
    }

    /**
     * @param Request $request
     * @param $transaction_id
     * @return array
     *
     * Used by Stripe
     */

    public function chargePayment(Request $request, $transaction_id){
        $payment = Payment::whereLocalTransactionId($transaction_id)->whereStatus('initial')->first();
        $ad = $payment->ad;

        //Determine which payment method is this
        if ($payment->payment_method == 'paypal') {

            // PayPal settings
            $paypal_action_url = "https://www.paypal.com/cgi-bin/webscr";
            if (get_option('enable_paypal_sandbox') == 1)
                $paypal_action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

            $paypal_email = get_option('paypal_receiver_email');
            $return_url = route('payment_success_url', $transaction_id);
            $cancel_url = route('payment_checkout', $transaction_id);
            $notify_url = route('paypal_notify_url', $transaction_id);

            $item_name = $payment->ad->title." (".ucfirst($payment->ad->price_plan).") ad posting";

            // Check if paypal request or response
            $querystring = '';

            // Firstly Append paypal account to querystring
            $querystring .= "?business=".urlencode($paypal_email)."&";

            // Append amount& currency (£) to quersytring so it cannot be edited in html
            //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
            $querystring .= "item_name=".urlencode($item_name)."&";
            $querystring .= "amount=".urlencode($payment->amount)."&";
            $querystring .= "currency_code=".urlencode($payment->currency)."&";

            $querystring .= "first_name=".urlencode($ad->user->first_name)."&";
            $querystring .= "last_name=".urlencode($ad->user->last_name)."&";
            $querystring .= "payer_email=".urlencode($ad->user->email)."&";
            $querystring .= "item_number=".urlencode($payment->local_transaction_id)."&";

            //loop for posted values and append to querystring
            foreach(array_except($request->input(), '_token') as $key => $value){
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }

            // Append paypal return addresses
            $querystring .= "return=".urlencode(stripslashes($return_url))."&";
            $querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
            $querystring .= "notify_url=".urlencode($notify_url);

            // Append querystring with custom field
            //$querystring .= "&custom=".USERID;

            // Redirect to paypal IPN
            header('location:'.$paypal_action_url.$querystring);
            exit();

        }elseif ($payment->payment_method == 'stripe'){

            $stripeToken = $request->stripeToken;
            \Stripe\Stripe::setApiKey(get_stripe_key('secret'));
            // Create the charge on Stripe's servers - this will charge the user's card
            try {
                $charge = \Stripe\Charge::create(array(
                    "amount" => ($payment->amount * 100), // amount in cents, again
                    "currency" => $payment->currency,
                    "source" => $stripeToken,
                    "description" => $payment->ad->title." (".ucfirst($payment->ad->price_plan).") ad posting"
                ));

                if ($charge->status == 'succeeded'){
                    $payment->status = 'success';
                    $payment->charge_id_or_token = $charge->id;
                    $payment->description = $charge->description;
                    $payment->payment_created = $charge->created;
                    $payment->save();

                    //Set publish ad by helper function
                    ad_status_change($ad->id, '1');

                    return ['success'=>1, 'msg'=> trans('app.payment_received_msg')];
                }
            } catch(\Stripe\Error\Card $e) {
                // The card has been declined
                $payment->status = 'declined';
                $payment->description = trans('app.payment_declined_msg');
                $payment->save();
                return ['success'=>0, 'msg'=> trans('app.payment_declined_msg')];
            }
        }

        return ['success'=>0, 'msg'=> trans('app.error_msg')];
    }

    /**
     * @param Request $request
     * @param $transaction_id
     * @return mixed
     *
     * Payment success url receive from paypal
     */

    public function paymentSuccess(Request $request, $transaction_id){

        /**
         * Check is this transaction paid via paypal IPN
         */
        $transaction_check = Payment::whereLocalTransactionId($transaction_id)->first();
        if ($transaction_check){
            if($transaction_check->status == 'success'){
                return redirect(route('my_ads'))->with('success', trans('app.payment_received_msg'));
            }elseif ($transaction_check->status == 'declined'){
                return redirect(route('my_ads'))->with('error', trans('app.payment_declined_msg'));
            }
        }

        //Start original logic

        $payment = Payment::whereLocalTransactionId($transaction_id)->whereStatus('initial')->first();
        $ad = $payment->ad;

        $paypal_action_url = "https://www.paypal.com/cgi-bin/webscr";
        if (get_option('enable_paypal_sandbox') == 1)
            $paypal_action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

        // STEP 1: read POST data
        // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
        // Instead, read raw POST data from the input stream.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

// STEP 2: POST IPN data back to PayPal to validate
        $ch = curl_init($paypal_action_url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        if( !($res = curl_exec($ch)) ) {
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        }
        curl_close($ch);

// STEP 3: Inspect IPN validation result and act accordingly
        if (strcmp ($res, "VERIFIED") == 0) {

            //Payment success, we are ready to live our ads
            $payment->status = 'success';
            $payment->charge_id_or_token = $request->txn_id;
            $payment->description = $request->item_name;
            $payment->payer_email = $request->payer_email;
            $payment->payment_created = strtotime($request->payment_date);
            $payment->save();

            //Set publish ad by helper function
            ad_status_change($ad->id, '1');

            return redirect(route('my_ads'))->with('success', trans('app.payment_received_msg'));

        } else if (strcmp ($res, "INVALID") == 0) {

            $payment->status = 'declined';
            $payment->description = trans('app.payment_declined_msg');
            $payment->save();
            return redirect(route('my_ads'))->with('error', trans('app.payment_declined_msg'));
        }


    }

    /**
     * @param Request $request
     * @param $transaction_id
     * @return mixed
     *
     * Ipn notify, receive from paypal
     */
    public function paypalNotify(Request $request, $transaction_id){
        $payment = Payment::whereLocalTransactionId($transaction_id)->whereStatus('initial')->first();
        $ad = $payment->ad;

        $paypal_action_url = "https://www.paypal.com/cgi-bin/webscr";
        if (get_option('enable_paypal_sandbox') == 1)
            $paypal_action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

        // STEP 1: read POST data
        // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
        // Instead, read raw POST data from the input stream.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
    // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

    // STEP 2: POST IPN data back to PayPal to validate
        $ch = curl_init($paypal_action_url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        // In wamp-like environments that do not come bundled with root authority certificates,
        // please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
        // the directory path of the certificate as shown below:
        // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        if( !($res = curl_exec($ch)) ) {
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        }
        curl_close($ch);

    // STEP 3: Inspect IPN validation result and act accordingly
        if (strcmp ($res, "VERIFIED") == 0) {

            //Payment success, we are ready to live our ads
            $payment->status = 'success';
            $payment->charge_id_or_token = $request->txn_id;
            $payment->description = $request->item_name;
            $payment->payer_email = $request->payer_email;
            $payment->payment_created = strtotime($request->payment_date);
            $payment->save();

            //Set publish ad by helper function
            ad_status_change($ad->id, '1');

            return redirect(route('my_ads'))->with('success', trans('app.payment_received_msg'));

        } else if (strcmp ($res, "INVALID") == 0) {

            $payment->status = 'declined';
            $payment->description = trans('app.payment_declined_msg');
            $payment->save();
            return redirect(route('my_ads'))->with('error', trans('app.payment_declined_msg'));
        }

    }


}
