<?php

namespace Webkul\SendcloudShipping\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Checkout\Models\CartAddress as CartAddressBaseModel;

class CartAddress extends CartAddressBaseModel
{
    protected $table = 'cart_address';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company_name',
        'vat_id',
        'address1',
        'address2',
        'city',
        'state',
        'postcode',
        'country',
        'phone',
        'address_type',
        'cart_id',
        'customer_id',
    ];

    /**
     * Get the shipping rates for the cart address.
     */
    public function shipping_rates()
    {
        return $this->hasMany(CartShippingRateProxy::modelClass());
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getNameAttribute():string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}