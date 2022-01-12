<?php

namespace Webkul\SendcloudShipping\Repositories;

use Webkul\SendcloudShipping\Models\Sendcloud;
use Storage;

class SendcloudRepository
{

    /**
     * Create a new repository instance.
     *
     * @param  Illuminate\Container\Container   $app
     * @return void
     */

    public function __construct()
    {
        // parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\SendcloudShipping\Contracts\Sendcloud';
    }

    /**
     * create sendcloud shipment
     *
     * @return mixed
     */
    public function createShipment($order, $weight)
    {
        $apiKey = core()->getConfigData('sales.carriers.sendcloud.api_key');
        $secretKey = core()->getConfigData('sales.carriers.sendcloud.secret_key');

        $billingAddress = $order->shipping_address()->first();
        $shipmentId = substr((string)$order->shipping_method, 10);

        $data['parcel'] = [
            "name" =>  $billingAddress->name,
            "company_name" => $billingAddress->company_name,
            "address" => $billingAddress->address1,
            "house_number" => $billingAddress->address2,
            "city" =>  $billingAddress->city,
            "postal_code" => $billingAddress->postcode,
            "telephone" => $billingAddress->phone,
            "request_label" => true,
            "email" => $billingAddress->email,
            "data" => [],
            "country" => $billingAddress->country,
            "shipment" => [
                "id" => $shipmentId
            ],

            "weight" => $weight,
            "order_number" => $order->id,
        ];

        $data_string = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://panel.sendcloud.sc/api/v2/parcels");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $apiKey.':'.$secretKey);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );

        $result = curl_exec($ch);

        $resultArray = json_decode($result);

        if (isset($resultArray) && $resultArray != null && !isset($resultArray->error)) {

            return $result;
        } else {
            if (isset($resultArray->error)) {

                return $resultArray;
            } else {
                return null;
            }
        }
    }

    public function getLabelPdf($shipmentId)
    {
        $apiKey = core()->getConfigData('sales.carriers.sendcloud.api_key');
        $secretKey = core()->getConfigData('sales.carriers.sendcloud.secret_key');

        $labelFormate = core()->getConfigData('sales.carriers.sendcloud.label');

        $shipmentFromApi = Sendcloud::where('shipment_id' , $shipmentId)->first();


        if (core()->getConfigData('sales.carriers.sendcloud.shipment_type') == 'manual'
            || $shipmentFromApi == null
        ) {
            return 'manual_shipment';
        }

        $parcelId = Sendcloud::where('shipment_id' , $shipmentId)->first()->parcel_id;

        if ($labelFormate == 4) {
            $url = 'https://panel.sendcloud.sc/api/v2/labels/label_printer/' . $parcelId;
        } else {
            $url = 'https://panel.sendcloud.sc/api/v2/labels/normal_printer/' . $parcelId . '?start_from=' . $labelFormate;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $apiKey.':'.$secretKey);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/pdf')
        );

        $resultdata = curl_exec($ch);

        $fileName = 'ShipmentLabel_' . $shipmentId . '_' . $labelFormate . '.pdf';

        $filepath = storage_path('app/public/shipping-label/') . $fileName ;

        if (Storage::exists($filepath) == false) {

            Storage::makeDirectory('shipping-label/');
        }

        $file = fopen($filepath, 'w+');
        fputs($file, $resultdata);

        fclose($file);

        return $filepath;
    }
}

