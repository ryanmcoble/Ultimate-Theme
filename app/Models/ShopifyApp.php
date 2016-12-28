<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyApp extends Model
{
    protected $table = 'apps';
    protected $fillable = [
        'name',
        'key'
    ];

    public function permissions() {
        return $this->hasMany('App\Models\AppPermission', 'app_id', 'id');
    }
}
