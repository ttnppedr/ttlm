<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function getItemsJson()
    {
        $result = [];
        $items = json_decode($this->items, true);
        foreach ($items as $item) {
            $merchandiseId = array_keys($item)[0];
            $merchandise = Merchandise::find($merchandiseId)->toArray();
            $merchandise['count'] = $item[$merchandiseId];
            $result[] = $merchandise;
        }

        return json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
