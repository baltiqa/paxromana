<?php

namespace App\Providers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Models\Order;



/**
 * Class GlobalTemplateServiceProvider
 * @package App\Providers
 * @codeCoverageIgnore
 */
class GlobalTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['account.orders.sales.navbar'], function ($view) {
            $view->with('processing', $this->processingOrdersCount());
            $view->with('accepted', $this->acceptedOrdersCount());
            $view->with('shipped', $this->shippedOrdersCount());
            $view->with('finalized', $this->finalizedOrdersCount());
            $view->with('disputed', $this->disputedOrdersCount());
            $view->with('cancelled', $this->cancelledOrdersCount());
        });

        view()->composer(['layouts.navbar'], function ($view) {
            $view->with('processing', $this->processingOrdersCount());
        });
   
    }


    /**
     * Get all the categories
     *
     */
    private function processingOrdersCount()
    {
      return Order::where('seller_id', auth()->user()->id)->process()->count();
    }
    private function acceptedOrdersCount()
    {
        return Order::where('seller_id', auth()->user()->id)->accepted()->count();
    }
    private function shippedOrdersCount()
    {
        return Order::where('seller_id', auth()->user()->id)->shipped()->count();
    }
    private function finalizedOrdersCount()
    {
        return Order::where('seller_id', auth()->user()->id)->finalized()->count();
    }
    private function disputedOrdersCount()
    {
        return Order::where('seller_id', auth()->user()->id)->dispute()->count();
    }
    private function cancelledOrdersCount()
    {
        return Order::where('seller_id', auth()->user()->id)->cancelled()->count();
    }
   
}
