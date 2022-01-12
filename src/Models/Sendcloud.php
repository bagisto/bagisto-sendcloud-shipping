<?php

namespace Webkul\SendcloudShipping\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\SendcloudShipping\Contracts\Sendcloud as SendcloudContract;

class Sendcloud extends Model implements SendcloudContract
{
    protected $table = 'sendcloud_shipping';

    protected $fillable = ['tracking_number', 'tracking_ur', 'order_id', 'parcel_id', 'shipment_id'];

    /**
     * Get the Category that belongs to the Supplier.
     */
    public function order()
    {
        return $this->belongsTo(\Webkul\Sales\Models\OrderProxy::modelClass(), 'order_id');
    }
}