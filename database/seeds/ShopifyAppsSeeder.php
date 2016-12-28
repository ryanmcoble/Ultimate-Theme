<?php

use Illuminate\Database\Seeder;

use App\Models\ShopifyApp;
use App\Models\AppPermission;

class ShopifyAppsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$apps = [
    		[
    			'name' => 'Theme Manager Suite',
    			'key'  => 'theme-manager-suite',
    			'permissions' => [
    				'read_themes',
    				'write_themes'
    			]
    		]
    	];

    	foreach($apps as $a) {
    		$app = ShopifyApp::where('key', $a['key'])->first();
    		if(!$app) {
    			$app = new ShopifyApp;
    			$app->name = $a['name'];
    			$app->key  = $a['key'];
    			$app->save();
    		}

            foreach($a['permissions'] as $p) {
                $permission = AppPermission::where('value', $p)->first();
                if(!$permission) {
                    $permission = new AppPermission;
                    $permission->app_id = $app->id;
                    $permission->value  = $p;
                    $permission->save();
                }
            }
    	}

    }
}
