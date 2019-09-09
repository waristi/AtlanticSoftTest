<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    public function children() {
        return $this->hasMany('App\Category', 'parent');
    }

}