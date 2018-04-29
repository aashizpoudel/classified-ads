<?php
/**
 * @return mixed
 * Custom functions made by themeqx
 */

/**
 * @param string $img (object)
 * @param bool $full_size
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function media_url($img = '', $full_size = false){
    $url_path = '';

    if ($img){
        if ($img->type == 'image'){
            if ($img->storage == 'public'){
                if ($full_size){
                    $url_path = asset('uploads/images/'.$img->media_name);
                }else{
                    $url_path = asset('uploads/images/thumbs/'.$img->media_name);
                }
            }elseif ($img->storage == 's3'){
                if ($full_size){
                    $url_path = \Illuminate\Support\Facades\Storage::disk('s3')->url('uploads/images/'.$img->media_name);
                }else{
                    $url_path = \Illuminate\Support\Facades\Storage::disk('s3')->url('uploads/images/thumbs/'.$img->media_name);
                }

            }
        }
    }else{
        $url_path = asset('uploads/placeholder.png');
    }

    return $url_path;
}

function slider_url($img = ''){
    $url_path = '';
    if ($img){
        if ($img->storage == 'public'){
            $url_path = asset('uploads/sliders/'.$img->media_name);
        }elseif ($img->storage == 's3'){
            $url_path = \Illuminate\Support\Facades\Storage::disk('s3')->url('uploads/sliders/'.$img->media_name);
        }
    }
    return $url_path;
}

function avatar_img_url($img = '', $source){
    $url_path = '';
    if ($img){
        if ($source == 'public'){
            $url_path = asset('uploads/avatar/'.$img);
        }elseif ($source == 's3'){
            $url_path = \Illuminate\Support\Facades\Storage::disk('s3')->url('uploads/avatar/'.$img);
        }
    }
    return $url_path;
}

/**
 * @return string
 * 
 * @return logo url
 */
function logo_url(){
    $url_path = '';
    $img = get_option('logo');
    $source = get_option('logo_storage');

    $exists = \Illuminate\Support\Facades\Storage::disk($source)->exists('uploads/logo/'.$img);
    if ( ! $exists)
        return false;

    if ($source == 'public'){
        $url_path = asset('uploads/logo/'.$img);
    }elseif ($source == 's3'){
        $url_path = \Illuminate\Support\Facades\Storage::disk('s3')->url('uploads/logo/'.$img);
    }

    return $url_path;
}

/**
 * @return mixed
 */
function current_disk(){
    $current_disk = \Illuminate\Support\Facades\Storage::disk(get_option('default_storage'));
    return $current_disk;
}

/**
 * @param string $option_key
 * @return string
 */
function get_option($option_key = ''){
    $get = \App\Option::where('option_key', $option_key)->first();
    if($get) {
        return $get->option_value;
    }
    return $option_key;
}



/**
 * @param string $text
 * @return mixed
 */
function get_text_tpl($text = ''){
    $tpl = ['[year]', '[copyright_sign]', '[site_name]'];
    $variable = [date('Y'), '&copy;', get_option('site_name')];
    
    $tpl_option = str_replace($tpl,$variable,$text);
    return $tpl_option;
}

/**
 * @param string $title
 * @param $model
 * @return string
 */

function unique_slug($title = '', $model = 'Ad'){
    $slug = str_slug($title);
    //get unique slug...
    $nSlug = $slug;
    $i = 0;

    $model = str_replace(' ','',"\App\ ".$model);
    while( ($model::whereSlug($nSlug)->count()) > 0){
        $i++;
        $nSlug = $slug.'-'.$i;
    }
    if($i > 0) {
        $newSlug = substr($nSlug, 0, strlen($slug)) . '-' . $i;
    } else
    {
        $newSlug = $slug;
    }
    return $newSlug;
}

/**
 * @param string $plan
 * @return int|string
 */
function get_ads_price($plan = 'regular'){
    $price = 0;

    if ($plan == 'regular') {
        if (get_option('ads_price_plan') == 'all_ads_paid') {
            $price = get_option('regular_ads_price');
        }
    }elseif($plan == 'premium'){
        if (get_option('ads_price_plan') != 'all_ads_free') {
            $price = get_option('premium_ads_price');
        }
    }

    return $price;
}

function get_languages(){
    $languages = \App\Language::all();
    return $languages;
}

/**
 * @param string $type
 * @return string
 *
 * @return stripe secret key or test key
 */

function get_stripe_key($type = 'publishable'){
    $stripe_key = '';

    if ($type == 'publishable'){
        if (get_option('stripe_test_mode') == 1){
            $stripe_key = get_option('stripe_test_publishable_key');
        }else{
            $stripe_key = get_option('stripe_live_publishable_key');
        }
    }elseif ($type == 'secret'){
        if (get_option('stripe_test_mode') == 1){
            $stripe_key = get_option('stripe_test_secret_key');
        }else{
            $stripe_key = get_option('sk_live_ojldRoMZ3j14I5pwpfCxidvT');
        }
    }

    return $stripe_key;
}

/**
 * @param int $ad_id
 * @param string $status
 */
function ad_status_change($ad_id = 0, $status = '1'){
    if ($ad_id > 0){
        $ad = \App\Ad::find($ad_id);
        
        if ($ad){
            $previous_status = $ad->status;
            //Publish ad
            $ad->status = $status;
            $ad->save();

            if ($previous_status == 0) {
                if ($status == 1) {
                    //Increase category product count by plus one
                    $category = $ad->category;
                    $category->product_count = $category->product_count + 1;
                    $category->save();

                    //Increase Sub category product count by plus one
                    $sub_category = $ad->sub_category;
                    $sub_category->product_count = $sub_category->product_count + 1;
                    $sub_category->save();
                    return true;
                }
            }


        }
    }

    return false;
}

/**
 * @return bool
 *
 * return all fa icon list
 */
function fa_icons(){
    $pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"\\\\(.+)";\s+}/';
    $subject =  file_get_contents(public_path('assets/font-awesome-4.4.0/css/font-awesome.css'));
    preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);

    foreach($matches as $match) {
        $icons[$match[1]] = $match[2];
    }
    ksort($icons);
    return $icons;
}

function category_classes(){
    $classes = [
        'green'     => 'green',
        'gold'      => 'gold',
        'purple'    => 'purple',
        'orange'    => 'orange',
        'brick'     => 'brick',
        'blue'      => 'blue',
        'honey'     => 'honey',
    ];
    
    return $classes;
}

/**
 * @param int $price
 * @return string
 */
function themeqx_price($price = 0){
    return get_option('currency_sign').' '.$price;
}

/**
 * @param int $price
 * @param int $negotiable
 * @return string
 */

function themeqx_price_ng($price = 0, $negotiable = 0){
    $ng = $negotiable ? ' ('.trans('app.negotiable').') ' : '';
    $show_price = '';
    if ($price > 0){
        $show_price = get_option('currency_sign').' '.$price;
    }
    return $show_price.$ng;
}

function update_option($key, $value){
    $option = \App\Option::firstOrCreate(['option_key' => $key]);
    $option -> option_value = $value;
    return $option->save();
}



function themeqx_classifieds_currencies(){
    return array(
        'AED' => 'United Arab Emirates dirham',
        'AFN' => 'Afghan afghani',
        'ALL' => 'Albanian lek',
        'AMD' => 'Armenian dram',
        'ANG' => 'Netherlands Antillean guilder',
        'AOA' => 'Angolan kwanza',
        'ARS' => 'Argentine peso',
        'AUD' => 'Australian dollar',
        'AWG' => 'Aruban florin',
        'AZN' => 'Azerbaijani manat',
        'BAM' => 'Bosnia and Herzegovina convertible mark',
        'BBD' => 'Barbadian dollar',
        'BDT' => 'Bangladeshi taka',
        'BGN' => 'Bulgarian lev',
        'BHD' => 'Bahraini dinar',
        'BIF' => 'Burundian franc',
        'BMD' => 'Bermudian dollar',
        'BND' => 'Brunei dollar',
        'BOB' => 'Bolivian boliviano',
        'BRL' => 'Brazilian real',
        'BSD' => 'Bahamian dollar',
        'BTC' => 'Bitcoin',
        'BTN' => 'Bhutanese ngultrum',
        'BWP' => 'Botswana pula',
        'BYR' => 'Belarusian ruble',
        'BZD' => 'Belize dollar',
        'CAD' => 'Canadian dollar',
        'CDF' => 'Congolese franc',
        'CHF' => 'Swiss franc',
        'CLP' => 'Chilean peso',
        'CNY' => 'Chinese yuan',
        'COP' => 'Colombian peso',
        'CRC' => 'Costa Rican col&oacute;n',
        'CUC' => 'Cuban convertible peso',
        'CUP' => 'Cuban peso',
        'CVE' => 'Cape Verdean escudo',
        'CZK' => 'Czech koruna',
        'DJF' => 'Djiboutian franc',
        'DKK' => 'Danish krone',
        'DOP' => 'Dominican peso',
        'DZD' => 'Algerian dinar',
        'EGP' => 'Egyptian pound',
        'ERN' => 'Eritrean nakfa',
        'ETB' => 'Ethiopian birr',
        'EUR' => 'Euro',
        'FJD' => 'Fijian dollar',
        'FKP' => 'Falkland Islands pound',
        'GBP' => 'Pound sterling',
        'GEL' => 'Georgian lari',
        'GGP' => 'Guernsey pound',
        'GHS' => 'Ghana cedi',
        'GIP' => 'Gibraltar pound',
        'GMD' => 'Gambian dalasi',
        'GNF' => 'Guinean franc',
        'GTQ' => 'Guatemalan quetzal',
        'GYD' => 'Guyanese dollar',
        'HKD' => 'Hong Kong dollar',
        'HNL' => 'Honduran lempira',
        'HRK' => 'Croatian kuna',
        'HTG' => 'Haitian gourde',
        'HUF' => 'Hungarian forint',
        'IDR' => 'Indonesian rupiah',
        'ILS' => 'Israeli new shekel',
        'IMP' => 'Manx pound',
        'INR' => 'Indian rupee',
        'IQD' => 'Iraqi dinar',
        'IRR' => 'Iranian rial',
        'ISK' => 'Icelandic kr&oacute;na',
        'JEP' => 'Jersey pound',
        'JMD' => 'Jamaican dollar',
        'JOD' => 'Jordanian dinar',
        'JPY' => 'Japanese yen',
        'KES' => 'Kenyan shilling',
        'KGS' => 'Kyrgyzstani som',
        'KHR' => 'Cambodian riel',
        'KMF' => 'Comorian franc',
        'KPW' => 'North Korean won',
        'KRW' => 'South Korean won',
        'KWD' => 'Kuwaiti dinar',
        'KYD' => 'Cayman Islands dollar',
        'KZT' => 'Kazakhstani tenge',
        'LAK' => 'Lao kip',
        'LBP' => 'Lebanese pound',
        'LKR' => 'Sri Lankan rupee',
        'LRD' => 'Liberian dollar',
        'LSL' => 'Lesotho loti',
        'LYD' => 'Libyan dinar',
        'MAD' => 'Moroccan dirham',
        'MDL' => 'Moldovan leu',
        'MGA' => 'Malagasy ariary',
        'MKD' => 'Macedonian denar',
        'MMK' => 'Burmese kyat',
        'MNT' => 'Mongolian t&ouml;gr&ouml;g',
        'MOP' => 'Macanese pataca',
        'MRO' => 'Mauritanian ouguiya',
        'MUR' => 'Mauritian rupee',
        'MVR' => 'Maldivian rufiyaa',
        'MWK' => 'Malawian kwacha',
        'MXN' => 'Mexican peso',
        'MYR' => 'Malaysian ringgit',
        'MZN' => 'Mozambican metical',
        'NAD' => 'Namibian dollar',
        'NGN' => 'Nigerian naira',
        'NIO' => 'Nicaraguan c&oacute;rdoba',
        'NOK' => 'Norwegian krone',
        'NPR' => 'Nepalese rupee',
        'NZD' => 'New Zealand dollar',
        'OMR' => 'Omani rial',
        'PAB' => 'Panamanian balboa',
        'PEN' => 'Peruvian nuevo sol',
        'PGK' => 'Papua New Guinean kina',
        'PHP' => 'Philippine peso',
        'PKR' => 'Pakistani rupee',
        'PLN' => 'Polish z&#x142;oty',
        'PRB' => 'Transnistrian ruble',
        'PYG' => 'Paraguayan guaran&iacute;',
        'QAR' => 'Qatari riyal',
        'RON' => 'Romanian leu',
        'RSD' => 'Serbian dinar',
        'RUB' => 'Russian ruble',
        'RWF' => 'Rwandan franc',
        'SAR' => 'Saudi riyal',
        'SBD' => 'Solomon Islands dollar',
        'SCR' => 'Seychellois rupee',
        'SDG' => 'Sudanese pound',
        'SEK' => 'Swedish krona',
        'SGD' => 'Singapore dollar',
        'SHP' => 'Saint Helena pound',
        'SLL' => 'Sierra Leonean leone',
        'SOS' => 'Somali shilling',
        'SRD' => 'Surinamese dollar',
        'SSP' => 'South Sudanese pound',
        'STD' => 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra',
        'SYP' => 'Syrian pound',
        'SZL' => 'Swazi lilangeni',
        'THB' => 'Thai baht',
        'TJS' => 'Tajikistani somoni',
        'TMT' => 'Turkmenistan manat',
        'TND' => 'Tunisian dinar',
        'TOP' => 'Tongan pa&#x2bb;anga',
        'TRY' => 'Turkish lira',
        'TTD' => 'Trinidad and Tobago dollar',
        'TWD' => 'New Taiwan dollar',
        'TZS' => 'Tanzanian shilling',
        'UAH' => 'Ukrainian hryvnia',
        'UGX' => 'Ugandan shilling',
        'USD' => 'United States dollar',
        'UYU' => 'Uruguayan peso',
        'UZS' => 'Uzbekistani som',
        'VEF' => 'Venezuelan bol&iacute;var',
        'VND' => 'Vietnamese &#x111;&#x1ed3;ng',
        'VUV' => 'Vanuatu vatu',
        'WST' => 'Samoan t&#x101;l&#x101;',
        'XAF' => 'Central African CFA franc',
        'XCD' => 'East Caribbean dollar',
        'XOF' => 'West African CFA franc',
        'XPF' => 'CFP franc',
        'YER' => 'Yemeni rial',
        'ZAR' => 'South African rand',
        'ZMW' => 'Zambian kwacha',
    );
    
}