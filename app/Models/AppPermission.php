<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppPermission extends Model
{
    protected $table = 'app_permissions';
    protected $fillable = [
        'app_id',
        'value'
    ];

    public function app() {
        return $this->belongsTo('App\Models\ShopifyApp', 'app_id', 'id');
    }
}
