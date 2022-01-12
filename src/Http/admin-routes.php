<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin/')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            Route::post('/shipments/create/{order_id}', 'Webkul\SendcloudShipping\Http\Controllers\Admin\ShipmentController@store')->defaults('_config', [
                'redirect' => 'admin.sales.orders.view'
            ])->name('sendcloud.admin.sales.shipments.store');

            Route::get('/label/{shipment_id}', 'Webkul\SendcloudShipping\Http\Controllers\Admin\ShipmentController@getLabel')->name('sendcloud.admin.sales.shipment.label.create');
        });
    });
});