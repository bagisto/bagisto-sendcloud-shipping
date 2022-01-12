<?php

namespace Webkul\SendcloudShipping\Helpers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartAddressRepository as CartAddress;
use Webkul\Core\Repositories\ChannelRepository as Channel;
use Exception;

class Helper
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Cart Address Object
     *
     * @var object
     */
    protected $cartAddress;

    /**
     * Ups Repository Object
     *
     * @var object
     */
    protected $upsRepository;

    /**
     * RateServiceWsdl
     *
     * @var string
     */
    protected $rateServiceWsdl;

    /**
     * ShipServiceWsdl
     *
     * @var string
     */
    protected $shipServiceWsdl;

    /**
     * SellerRepository object
     *
     * @var object
    */
    protected $channel;


    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Checkout\Repositories\CartAddressRepository  $cartAddress;
     * @param \Webkul\Core\Repositories\ChannelRepository $channel
     */
    public function __construct(
        CartAddress $cartAddress,
        Channel $channel
    )
    {
        $this->_config = request('_config');

        $this->cartAddress = $cartAddress;

        $this->channel = $channel;
    }

    /**
     * display methods from response
     *
     * @return array
    */
    public function getApiResponse()
    {
        return $this->getServices();
    }

    /**
     * get all shipping methods
     *
     * @return array
     */
    protected function getServices()
    {
        $apiKey = core()->getConfigData('sales.carriers.sendcloud.api_key');
        $secretKey = core()->getConfigData('sales.carriers.sendcloud.secret_key');
        $shippingMethods =null;

        try {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://panel.sendcloud.sc/api/v2/shipping_methods");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $apiKey.':'.$secretKey);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/json')
            );

            $errors = curl_error($ch);
            $result = curl_exec($ch);

            $data_string = json_decode($result);

            if (isset($data_string->error)) {
                throw new Exception($data_string->error->message);

            } else {
                $shippingMethods = $this->getfilterShipping($data_string);
            }

        }  catch(\Exception $e) {
            $e->getMessage();
        }

        return $shippingMethods;
    }

    /**
     * filter shipping seriveces
     *
     * @param array $shippingMethods
     */
    protected function getfilterShipping($shippingMethods)
    {
        $selectedServices = explode(',' , core()->getConfigData('sales.carriers.sendcloud.services'));

        $cart = Cart::getCart();
        $cartCounty = $cart->shipping_address->country;

        // Print country wise prices for all enabled shipping methods
        foreach ($shippingMethods as $shippingMethod) {
            foreach ($shippingMethod as $key => $shippingServices) {
                if (in_array($shippingServices->id, $selectedServices)) {

                    foreach ($shippingServices->countries as $countryWiseShipping) {
                        if ($countryWiseShipping->iso_2 == $cartCounty) {

                            $price = $countryWiseShipping->price;

                            $services[$shippingServices->id] = [
                                'name' => $shippingServices->name,
                                'price' => $price,
                            ];
                        }
                    }
                }
            }
        }

        return $services;
    }
}