<?php

namespace Webkul\SendcloudShipping\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class SendcloudShippingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/admin-routes.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'sendcloud');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'sendcloud');

        // $this->overrideModels();

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/sales/shipments/create.blade.php' => resource_path('views/vendor/admin/sales/shipments/create.blade.php')
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/sales/shipments/view.blade.php' => resource_path('views/vendor/admin/sales/shipments/view.blade.php')
        ]);

        //default checkout page
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/checkout/onepage/shipping.blade.php' => resource_path('themes/default/views/checkout/onepage/shipping.blade.php')
        ]);

        //default customer info page
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/checkout/onepage/customer-info.blade.php' => resource_path('themes/default/views/checkout/onepage/customer-info.blade.php')
        ]);

        // //default review page
        // $this->publishes([
        //     __DIR__ . '/../Resources/views/shop/default/checkout/onepage/review.blade.php' => resource_path('themes/default/views/checkout/onepage/review.blade.php')
        // ]);

        // //default onepage page
        // $this->publishes([
        //     __DIR__ . '/../Resources/views/shop/default/checkout/onepage.blade.php' => resource_path('themes/default/views/checkout/onepage.blade.php')
        // ]);




        // //velocity checkout page
        // $this->publishes([
        //     __DIR__ . '/../Resources/views/shop/velocity/checkout/onepage/shipping.blade.php' => resource_path('themes/velocity/views/checkout/onepage/shipping.blade.php')
        // ]);

        // //velocity checkout page
        // $this->publishes([
        //     __DIR__ . '/../Resources/views/shop/velocity/checkout/onepage/customer-info.blade.php' => resource_path('themes/velocity/views/checkout/onepage/customer-info.blade.php')
        // ]);

        //velocity checkout page
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/checkout/onepage/customer-new-form.blade.php' => resource_path('themes/velocity/views/checkout/onepage/customer-new-form.blade.php')
        ]);

        // //velocity review page
        // $this->publishes([
        //     __DIR__ . '/../Resources/views/shop/velocity/checkout/onepage/review.blade.php' => resource_path('themes/velocity/views/checkout/onepage/review.blade.php')
        // ]);



        //velocity one page
        // $this->publishes([
        //     __DIR__ . '/../Resources/views/shop/velocity/checkout/onepage.blade.php' => resource_path('themes/velocity/views/checkout/onepage.blade.php')
        // ]);

        //customer address edit page
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/customers/account/address/edit.blade.php' => resource_path('themes/default/views/customers/account/address/edit.blade.php')
        ]);

        // //customer address create page
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/customers/account/address/create.blade.php' => resource_path('themes/default/views/customers/account/address/create.blade.php')
        ]);



        //velocity customer addresss create
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/customers/account/address/create.blade.php' => resource_path('themes/velocity/views/customers/account/address/create.blade.php')
        ]);

        //velocity customer address edit
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/customers/account/address/edit.blade.php' => resource_path('themes/velocity/views/customers/account/address/edit.blade.php')
        ]);


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Override the existing models
     */
    // public function overrideModels()
    // {
    //     $this->app->concord->registerModel(
    //         \Webkul\Checkout\Contracts\CartAddress::class, \Webkul\SendcloudShipping\Models\CartAddress::class
    //     );
    // }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php', 'carriers'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}
