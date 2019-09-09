<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    public function category(){
        return $this->belongsTo('App\Category', 'categories_id');
    }

    public function galeria(){
        return $this->hasMany('App\ProductGaleria', 'products_id');
    }
}