<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    protected $guarded = [];

    public function stream()
    {
        return $this->belongsTo('App\Stream');
    }
}
