<?php

namespace Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Models\Tickets;
use App\Models\ReportedListing;
use App\Models\Dispute;


class PanelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');


        view()->composer(['panel::layouts.sidebar'], function ($view) {
            $view->with('tickets_number', $this->countTickets());
            $view->with('disputes_number', $this->countDisputes());
            $view->with('reports_number', $this->countReports());

        });


    }

    public function countTickets(){
        $ticket = Tickets::where('closed_by',null)->count();

        return $ticket;
    }

    public function countDisputes(){

        $disputes = Dispute::where('closed_by',null)->count();

        return $disputes;
        
    }

    public function countReports(){
        
        $reports = ReportedListing::where('active',1)->count();

        return $reports;

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('panel.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'panel'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/panel');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/panel';
        }, \Config::get('view.paths')), [$sourcePath]), 'panel');
        
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/panel');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'panel');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'panel');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
