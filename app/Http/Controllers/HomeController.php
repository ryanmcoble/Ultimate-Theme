<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App;

use App\Models\Shop;
use App\Models\ShopifyApp;

class HomeController extends Controller
{
	public function index() {
		// authenticated with shopify
		if(session('shop')) {
			$shop    = Shop::where('permanent_domain', session('shop'))->first();
			$api_key = env('API_KEY');

			return view('dashboard')->with(['shop' => $shop, 'api_key' => $api_key]);
		}

		// not authenticated
		$apps = ShopifyApp::all();
		return view('index')->with(['apps' => $apps]);
	}

	public function doInstall(Request $req) {
		// validate request
		$this->validate($req, [
			'app_id'   => 'required',
			'shop_url' => 'required'
		]);

		$appId = $req->input('app_id');

		// quick and simple way to remove https:// and http://
		$shopURL = $req->input('shop_url');
		$shopURL = str_ireplace('https://', '', $shopURL);
		$shopURL = str_ireplace('http://', '', $shopURL);

		// get the permissions for a given shop
		$permissions = [];
		$shop = Shop::where('permanent_domain', $shopURL)->with('permissions')->first();
		if($shop && $shop->permissions) {
			foreach($shop->permissions as $perm) {
				$permissions[] = $perm->value;
			}
		}

		// get the app being currently installed
		$app = ShopifyApp::where('id', $appId)->with('permissions')->first();
		if(!$app) {
			return redirect()->back()->withErrors(['App not found!'])->withInput();
		}

		// get all app permissions
		if($app->permissions) {
			foreach($app->permissions as $perm) {
				$permissions[] = $perm->value;
			}
		}

		// generate an install url from the provided shop url
		$shopify = App::make('ShopifyAPI', [
			'API_KEY'     => env('API_KEY'),
			'SHOP_DOMAIN' => $shopURL
		]);
		$installURL = $shopify->installURL(['permissions' => $permissions, 'redirect' => env('DOMAIN') . '/shopify/install_or_auth' /*'?app_id=' . $app->id*/]);

		// redirect to shopify app install page
		return redirect($installURL);
	}
}
