<?php

namespace Webkul\SendcloudShipping\Carriers;

use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Facades\Cart;

class Sendcloud extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'sendcloud';

    /**
     * Returns rate for flatrate
     *
     * @return array
     */
    public function calculate()
    {
        if (! $this->isAvailable())
            return false;

        $cart = Cart::getCart();

        if(core()->getConfigData('sales.carriers.sendcloud.rate_mode') == 'fixed') {

            //get fixed rate
            $grandTotal = $cart->grand_total;
            $freeShippingFrom = core()->getConfigData('sales.carriers.sendcloud.free_shipping_from');

            $object = new CartShippingRate;

            $object->carrier = 'sendcloud';
            $object->carrier_title = $this->getConfigData('title');
            $object->method = 'sendcloud_fixed';
            $object->method_title = $this->getConfigData('title');
            $object->method_description = $this->getConfigData('description');

            if ($grandTotal > $freeShippingFrom) {

                $object->price = 0;
                $object->base_price = 0;
            } else {
                if ($this->getConfigData('type') == 'per_unit') {
                    foreach ($cart->items as $item) {
                        if ($item->product->getTypeInstance()->isStockable()) {

                            $object->price += core()->convertPrice($this->getConfigData('rate')) * $item->quantity;

                            $object->base_price += $this->getConfigData('rate') * $item->quantity;
                        }
                    }
                } else {
                    $object->price = core()->convertPrice($this->getConfigData('rate'));
                    $object->base_price = $this->getConfigData('rate');
                }
            }

            return $object;
        } else {

            //get rate from the api response
            $grandTotal = $cart->sub_total;

            $freeShippingFrom = core()->getConfigData('sales.carriers.sendcloud.free_shipping_from');

            $rate = $this->getConfigData('rate');

            $shippingMethods = [];

            $shippingHelper = app('Webkul\SendcloudShipping\Helpers\Helper');

            $data = $shippingHelper->getApiResponse();

            if (! isset($data)) {
                return false;
            }

            if (isset($data) && count($data) > 0) {

                foreach ($data as $code => $sendCloudServices) {

                    $object = new CartShippingRate;
                    $object->carrier = 'sendcloud';
                    $object->carrier_title = $this->getConfigData('title');
                    $object->method = 'sendcloud_'.$code;
                    $object->method_title = $sendCloudServices['name'];
                    $object->method_description = $this->getConfigData('title');

                    if ($grandTotal > $freeShippingFrom) {

                        $object->price = 0;
                        $object->base_price = 0;
                    } else {
                        if ($this->getConfigData('type') == 'per_unit') {

                            foreach ($cart->items as $item) {

                                if ($item->product->getTypeInstance()->isStockable()) {

                                    $object->price += core()->convertPrice($sendCloudServices['price']) * $item->quantity + $rate;

                                    $object->base_price += $sendCloudServices['price'] * $item->quantity + $rate;
                                }
                            }
                        } else {
                            $object->price = core()->convertPrice($sendCloudServices['price'] + $rate);
                            $object->base_price = $sendCloudServices['price'] + $rate;
                        }
                    }

                    array_push($shippingMethods, $object);
                }

                return $shippingMethods;
            }
        }
    }
}