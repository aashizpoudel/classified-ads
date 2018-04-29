<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_Category extends Model
{
    protected $table = 'categories';
    
    public function parentCategory(){
        return $this->belongsTo(Category::class);
    }
}
