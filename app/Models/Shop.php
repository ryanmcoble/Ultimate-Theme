<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Exception;
use App;

class Shop extends Model
{
    protected $table = 'shops';
    protected $fillable = [
        'shopify_id',
        'shop_owner_email',
        'public_domain',
        'permanent_domain',
        'access_token'
    ];

    // get the permissions for a shop
    public function permissions() {
        return $this->hasMany('App\Models\ShopPermission', 'shop_id', 'id');
    }

    // get shop's files
    // public function files() {
    // 	return $this->hasMany('App\File');
    // }


    // format the domain to handle weird stuff
    private function formatDomain($domain = '') {
    	$noProtocol = preg_replace('/(http(s)?:\/\/)?/', '', $domain);

    	if(strpos($noProtocol, '/')) {
    		return substr($noProtocol, 0, strpos($noProtocol, '/'));
    	}

    	return $noProtocol;
    }

    // set the domain
    public function setDomain($domain) {
    	$this->permanent_domain = $this->formatDomain($domain);
    }

    // encryption / decryption of access token because we should not be storing them unencrypted
    private function encrypt($text = '', $key = '') {
    	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
    private function decrypt($text = '', $key = '') {
    	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    // get the access token
    public function getAccessToken() {
    	return $this->decrypt($this->access_token, env('ACTK_KEY'));
    }

    // set the access token
    public function setAccessToken($accessToken) {
    	return $this->encrypt($accessToken, env('ACTK_KEY')); // just for testing
    }

    // use the shopify api to update any shop data
    public static function updateShopInfo($shop = null) {
        // in case something weird happen, wrap in try catch
        try {
            $shopify = App::make('ShopifyAPI', [
                'API_KEY'      => env('API_KEY'),
                'API_SECRET'   => env('API_SECRET'),
                'SHOP_DOMAIN'  => $shop->permanent_domain,
                'ACCESS_TOKEN' => $shop->access_token,
            ]);

            $shopData = $shopify->call(['URL' => '/admin/shop.json']);

            if($shopData) {
                $shop->shopify_id = $shopData->shop->id; // don't know why this would change but just in case
                $shop->public_domain = $shopData->shop->domain;
                $shop->setDomain($shopData->shop->myshopify_domain);
                // $shop->shop_type = $shopData->shop->plan_name (for the future)
                $shop->shop_owner_email = $shopData->shop->email;
                $shop->save();
            }
        }
        catch(Exception $e) {
            Log::error('Shopify API Error: ' . $e->getMessage());
        }
    }
}
