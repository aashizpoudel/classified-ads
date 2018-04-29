<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];
    
    public function ad(){
        return $this->belongsTo(Ad::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function created_at_datetime(){
        $created_date_time = $this->created_at->timezone(get_option('default_timezone'))->format(get_option('date_format_custom').' '.get_option('time_format_custom'));
        return $created_date_time;
    }

    public function payment_completed_at(){
        $created_date_time = '';
        if ($this->payment_created){
            $created_date_time = Carbon::createFromTimestamp($this->payment_created, get_option('default_timezone'))->format(get_option('date_format_custom').' '.get_option('time_format_custom'));
        }
        return $created_date_time;
    }
}
