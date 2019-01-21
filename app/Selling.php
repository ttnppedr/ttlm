<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    protected $guarded = [];

    public function getMerchandiseJson()
    {
        $merchandise = Merchandise::find($this->merchandise_id)->toArray();

        return json_encode($merchandise, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function merchandise()
    {
        return $this->belongsTo('App\Merchandise');
    }
}
