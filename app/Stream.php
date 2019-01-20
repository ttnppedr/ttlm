<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $guarded = [];

    public function merchandises()
    {
        return $this->hasMany('App\Merchandise');
    }
}
