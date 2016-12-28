<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App;

use App\Models\Shop;

class ShopifyWebhookController extends Controller
{
	// uninstall app webhook
	public function onUninstall(Request $req) {
	    $shop = Shop::where('permanent_domain', $req->input('myshopify_domain'))->first();

	    if($shop) {
	        $shop->delete();
	    }

	    return 'Thank you webhook robot!';
	}
}
