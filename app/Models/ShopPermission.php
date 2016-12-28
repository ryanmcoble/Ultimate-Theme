<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopPermission extends Model
{
    protected $table = 'shop_permissions';
    protected $fillable = [
    	'shop_id',
        'app_id',
        'value'
    ];

    public function shop() {
        return $this->belongsTo('App\Models\Shop', 'shop_id', 'id');
    }

    public function app() {
        return $this->belongsTo('App\Models\ShopifyApp', 'app_id', 'id');
    }
}
