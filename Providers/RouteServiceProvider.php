<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Hashids;
use Date;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        #Route::model('listing', \App\Models\Listing::class);

        Route::bind('listing', function($value, $route) {
            if(Hashids::decode($value) == null) {
                abort(404);
            }

            $id = Hashids::decode($value)[0];
           
			return \App\Models\Listing::findOrFail($id);
        });	
        
        Route::bind('checkout', function($value, $route) {
            if(Hashids::decode($value) == null) {
                abort(404);
            }

            $id = Hashids::decode($value)[0];
			return \App\Models\Listing::findOrFail($id);
        });		
        
	

	Route::bind('user', function($value, $route) {
            $user = \App\Models\User::where('username', $value)->first();
            if($user == null) {
                abort(404);
            }
			return $user;
	});


        Route::bind('purchase-history', function($value, $route) {
            if(Hashids::connection('order')->decode($value) == null) {
                abort(404);
            }

            $id = Hashids::connection('order')->decode($value)[0];
            return \App\Models\Order::findOrFail($id);
        });
        Route::bind('order', function($value, $route) {
            if(Hashids::connection('order')->decode($value) == null) {
                abort(404);
            }
            $id = Hashids::connection('order')->decode($value)[0];
            return \App\Models\Order::findOrFail($id);
        });
        Route::bind('dispute', function($value, $route) {
            if(Hashids::connection('order')->decode($value) == null) {
                abort(404);
            }
            $id = Hashids::connection('order')->decode($value)[0];
            return \App\Models\Order::findOrFail($id);
        });
        Route::bind('edit', function($value, $route) {
            if(Hashids::decode($value) == null) {
                abort(404);
            }
            
            $id = Hashids::decode($value)[0];
            return \App\Models\Listing::findOrFail($id);
        });
        /*
                Route::bind('orders', function($value, $route) {
                    return \App\Models\Order::where('id', $value)->first();
                });*/
		#Route::fakeIdModel('listing', 'App\Models\Listing');
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
