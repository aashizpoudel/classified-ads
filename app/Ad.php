<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ad extends Model
{
    protected $guarded = [];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }
    public function state(){
        return $this->belongsTo(State::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function sub_category(){
        return $this->belongsTo(Sub_Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function scopeActivePremium($query){
        return $query->whereStatus('1')->wherePricePlan('premium');
    }

    public function scopeActiveRegular($query){
        return $query->whereStatus('1')->wherePricePlan('regular');
    }
    public function scopeActiveUrgent($query){
        return $query->whereStatus('1')->whereMarkAdUrgent('1');
    }
    public function scopeActive($query){
        return $query->whereStatus('1');
    }
    public function scopeBusiness($query){
        return $query->whereType('business');
    }
    public function scopePersonal($query){
        return $query->whereType('personal');
    }
    public function feature_img(){
        $feature_img = $this->hasOne(Media::class)->whereIsFeature('1');
        if (! $feature_img){
            $feature_img = $this->hasOne(Media::class)->first();
        }
        return $this->hasOne(Media::class);
    }
    public function media_img(){
        return $this->hasMany(Media::class)->whereType('image');
    }

    /**
     * @return bool
     */
    
    public function is_published(){
        if ($this->status == 1)
            return true;
        return false;
    }
    
    public function full_address(){
        $location = '';

        if($this->address != '')
            $location .= $this->address.", ";
        if($this->city != '')
            $location .= "<a href='".route('listing', ['city' => $this->city->id])."'> {$this->city->city_name}</a>, ";
        if($this->state != '')
            $location .= "<a href='".route('listing', ['state' => $this->state->id])."'> {$this->state->state_name}</a>, ";
        if($this->country != '')
            $location .= "<a href='".route('listing', ['country' => $this->country->id])."'> {$this->country->country_name}</a>";
        return $location;
    }

    public function posting_datetime(){
        $created_date_time = $this->created_at->timezone(get_option('default_timezone'))->format(get_option('date_format_custom').' '.get_option('time_format_custom'));
        return $created_date_time;
    }
    
    public function status_context(){
        $status = $this->status;
        $html = '';
        switch ($status){
            case 0:
                $html = '<span class="text-muted">'.trans('app.pending').'</span>';
                break;
            case 1:
                $html = '<span class="text-success">'.trans('app.published').'</span>';
                break;
            case 2:
                $html = '<span class="text-warning">'.trans('app.blocked').'</span>';
                break;
        }
        return $html;
    }

    public function is_my_favorite(){
        if (! Auth::check())
            return false;
        $user = Auth::user();

        $favorite = Favorite::whereUserId($user->id)->whereAdId($this->id)->first();
        if ($favorite){
            return true;
        }else{
            return false;
        }
    }

    public function reports(){
        return $this->hasMany(Report_ad::class);
    }

    public function increase_impression(){
        $this->max_impression = $this->max_impression +1;
        $this->save();
    }
    
}
