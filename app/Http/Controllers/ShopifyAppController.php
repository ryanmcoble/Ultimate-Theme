<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Exception;
use App;
use Log;
use Session;

use App\Models\Shop;

class ShopifyAppController extends Controller
{
    /**
     * Main method for authenticating with Shopify
     */
    public function installOrAuthenticate(Request $req) {
        // check for code from shopify (this happen on new install)
        if($req->has('code')) {

            $shopURL = $req->input('shop');
            $code    = $req->input('code');

            Log::info('New install: ' . $shopURL);

            $shopify = App::make('ShopifyAPI', [
                'API_KEY'     => env('API_KEY'),
                'API_SECRET'  => env('API_SECRET'),
                'SHOP_DOMAIN' => $shopURL
            ]);


            // attempt to get the access token
            $accessToken = '';
            try {
                // oauth verification
                $verify = $shopify->verifyRequest($req->all());
                if(!$verify) {
                    Log::error('Unable to verify oauth authentication');
                    return ;
                }

                $accessToken = $shopify->getAccessToken($code);
                if(!$accessToken) {
                    throw new Exception('Shopify Error: Access Token Error!');
                }
            }
            catch(Exception $e) {
                Log::error($e->getMessage());
                return ;
            }


            // check to see if we already have the shop record stored
            $shop = Shop::where('permanent_domain', $shopURL)->first();
            if(!$shop) {
                // add a new shop record
                $shop = new Shop;
            }

            // save or update shop data
            $shop->setDomain($shopURL);
            $shop->access_token = $accessToken;
            $shop->save();

            Shop::updateShopInfo($shop);

            // create webhook for uninstall (will do later)
            $webhookData = [
                'webhook' => [
                    'topic'   => 'app/uninstalled',
                    'address' => env('DOMAIN') . '/shopify/webhooks/uninstall',
                    'format'  => 'json'
                ]
            ];

            // always with the try catches for api call, past experiences and all lol
            try {
                $shopify->setup(['ACCESS_TOKEN' => $shop->access_token]);
                $shopify->call(['URL' => '/admin/webhooks.json', 'METHOD' => 'POST', 'DATA' => $webhookData]);
            }
            catch(Exception $e) {
                Log::error('Something weird with webhooks... ' . $e->getMessage());
            }

            // store shop in session, of course, duh
            Session::put('shop', $shop->permanent_domain);

            return redirect('/'); // or something idk yet
        }
        else { // authenticating from store apps screen, after first install

            $shopURL = $req->has('shop') ? $req->input('shop') : Session::get('shop');
            if(!$shopURL) {
               return 'Session ended: go back to apps and reopen the application';
            }

            $shop = Shop::where('permanent_domain', $shopURL)->first();
            if($shop) {
                // so everything seems to have gone good
                // update shop info (in case it has changed)
                Shop::updateShopInfo($shop);

                // store the shop in the session, so authentication will persist
                Session::put('shop', $shop->permanent_domain);

                return redirect('/'); // or something idk yet
            }
            // else {

            //     // no shop at this point means something weird happened
            //     Log::error('Something weird happened with the authentication of ' . $shopURL);

            //     // lets redirect them back to the install url
            //     $shopify = App::make('ShopifyAPI', [
            //         'API_KEY'     => env('API_KEY'),
            //         'SHOP_DOMAIN' => $shopURL
            //     ]);
                
            //     return redirect($shopify->installURL(['permissions' => ['read_themes', 'write_themes'], 'redirect' => env('DOMAIN') . '/shopify/install_or_auth']));
            // }
        }
    }
}