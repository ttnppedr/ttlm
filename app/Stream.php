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

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function getSellings()
    {
        $merchandises = $this->merchandises->toArray();
        $merchandiseId = array_map(function ($m) {
            return $m['id'];
        }, $merchandises);

        return Selling::whereIn('merchandise_id', $merchandiseId)->get();
    }
}
