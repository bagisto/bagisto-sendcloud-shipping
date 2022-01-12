<?php

return [
    [
        'key' => 'sales.carriers.sendcloud',
        'name' => 'sendcloud::app.admin.system.sendcloud',
        'sort' => 3,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'sendcloud::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true
            ], [
                'name'          => 'description',
                'title'         => 'sendcloud::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true
            ], [
                'name'          => 'active',
                'title'         => 'sendcloud::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'       => 'type',
                'title'      => 'admin::app.admin.system.type',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'options'    => [
                    [
                        'title' => 'Per Unit',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'Per Order',
                        'value' => 'per_order',
                    ]
                ],
                'validation'    => 'required_if:active,1',
            ], [
                'name'          => 'api_key',
                'title'         => 'sendcloud::app.admin.system.api-key',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true
            ], [
                'name'          => 'secret_key',
                'title'         => 'sendcloud::app.admin.system.api-secret',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true
            ], [
                'name'    => 'label',
                'title'   => 'sendcloud::app.admin.system.label',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'options' => [
                    [
                        'title' => 'A4 Top Left',
                        'value' => '0',
                    ], [
                        'title' => 'A4 Top Right',
                        'value' => '1',
                    ], [
                        'title' => 'A4 Bottom Right',
                        'value' => '2',
                    ], [
                        'title' => 'A4 Bottom Left',
                        'value' => '3',
                    ], [
                        'title' => 'A6 Full Page',
                        'value' => '4',
                    ],
                ],
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true

            ], [
                'name'    => 'rate_mode',
                'title'   => 'sendcloud::app.admin.system.mode',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'options' => [
                    [
                        'title' => 'Fixed Rate',
                        'value' => 'fixed',
                    ], [
                        'title' => 'Api Rate',
                        'value' => 'api',
                    ],
                ],
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true

            ], [
                'name'    => 'shipment_type',
                'title'   => 'sendcloud::app.admin.system.shipment-type',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'options' => [
                    [
                        'title' => 'From API',
                        'value' => 'api',
                    ], [
                        'title' => 'Manual',
                        'value' => 'manual',
                    ],
                ],
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true

            ], [
                'name'          => 'free_shipping_from',
                'title'         => 'sendcloud::app.admin.system.free-shipping',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name' => 'rate',
                'title' => 'sendcloud::app.admin.system.rate',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation' => 'required_if:active,1|decimal',
                'channel_based' => false,
                'locale_based' => true,
                'info' => 'sendcloud::app.admin.system.note',
            ], [
                'name' => 'services',
                'title' => 'sendcloud::app.admin.system.services',
                'type' => 'multiselect',
                'depend' => 'rate_mode:api',
                'info' => 'sendcloud::app.admin.system.service-note',
                'options' => [
                    [
                        'title' => 'Unstamped letter',
                        'value' => '8',
                    ], [
                        'title' => 'DHL Parcel Connect 0-2kg',
                        'value' => '359',
                    ], [
                        'title' => 'DHL Parcel Connect 0-2kg to ParcelShop',
                        'value' => '1315'
                    ], [
                        'title' => 'DHL Parcel Connect 15-31.5kg',
                        'value' => '356'
                    ], [
                        'title' => 'DHL Parcel Connect 15-31.5kg to ParcelShop',
                        'value' => '1318'
                    ], [
                        'title' => 'DHL Parcel Connect 2-5kg',
                        'value' => '358'
                    ], [
                        'title' => 'DHL Parcel Connect 2-5kg to ParcelShop',
                        'value' => '1316'
                    ], [
                        'title' => 'DHL Parcel Connect 5-15kg',
                        'value' => '357'
                    ], [
                        'title' => 'DHL Parcel Connect 5-15kg to ParcelShop',
                        'value' => '1317'
                    ], [
                        'title' => 'DHLForYou Drop Off',
                        'value' => '117'
                    ], [
                        'title' => 'DHLForYou Letterbox Drop Off',
                        'value' => '492'
                    ], [
                        'title' => 'DPD Home',
                        'value' => '68'
                    ], [
                        'title' => 'DPD Pickup Point',
                        'value' => '109'
                    ], [
                        'title' => 'PostNL abroad 0-23kg',
                        'value' => '3'
                    ], [
                        'title' => 'PostNL evening delivery + home address only 0-23kg',
                        'value' => '98'
                    ], [
                        'title' => 'PostNL evening delivery + home address only 23-31.5kg',
                        'value' => '291'
                    ], [
                        'title' => 'PostNL evening delivery + signature on delivery + home address only 0-23kg',
                        'value' => '97'
                    ], [
                        'title' => 'PostNL evening delivery + signature on delivery + home address only 23-31.5kg',
                        'value' => '292'
                    ], [
                        'title' => 'PostNL GlobalPack',
                        'value' => '84'
                    ], [
                        'title' => 'PostNL home address only 0-23kg',
                        'value' => '6'
                    ], [
                        'title' => 'PostNL home address only 23-31.5kg',
                        'value' => '294'
                    ], [
                        'title' => 'PostNL Mailbox Parcel Extra 0-2kg',
                        'value' => '39'
                    ], [
                        'title' => 'PostNL service point 0-23kg',
                        'value' => '7'
                    ], [
                        'title' => 'PostNL service point 23-31.5kg',
                        'value' => '293'
                    ], [
                        'title' => 'PostNL signature on delivery + home address only 0-23kg',
                        'value' => '5'
                    ], [
                        'title' => 'PostNL signature on delivery + home address only 23-31.5kg',
                        'value' => '295'
                    ], [
                        'title' => 'PostNL Standard 0-23kg',
                        'value' => '1'
                    ], [
                        'title' => 'PostNL Standard 23-31.5kg',
                        'value' => '273'
                    ], [
                        'title' => 'PostNL with signature 0-23kg',
                        'value' => '2'
                    ], [
                        'title' => 'PostNL with signature 23-31.5kg',
                        'value' => '297'
                    ], [
                        'title' => 'UPS Standard 30-40kg',
                        'value' => '123'
                    ], [
                        'title' => 'UPS Standard 40-50kg',
                        'value' => '124'
                    ], [
                        'title' => 'UPS Standard 50-60kg',
                        'value' => '125'
                    ], [
                        'title' => 'UPS Standard 60-70kg',
                        'value' => '126'
                    ], [
                        'title' => 'UPS Express Saver 0-3kg',
                        'value' => '127'
                    ], [
                        'title' => 'UPS Express Saver 3-6kg',
                        'value' => '128'
                    ], [
                        'title' => 'UPS Express Saver 6-10kg',
                        'value' => '129'
                    ], [
                        'title' => 'UPS Express Saver 10-15kg',
                        'value' => '130'
                    ], [
                        'title' => 'UPS Express Saver 15-20kg',
                        'value' => '131'
                    ], [
                        'title' => 'UPS Express Saver 20-30kg',
                        'value' => '132'
                    ], [
                        'title' => 'UPS Express Saver 30-40kg',
                        'value' => '133'
                    ], [
                        'title' => 'UPS Express Saver 50-60kg',
                        'value' => '135'
                    ], [
                        'title' => 'UPS Express Saver 40-50kg',
                        'value' => '134'
                    ], [
                        "title" => "UPS Standard 3-6kg",
                        "value" => '138'
                    ], [
                        "title" => "UPS Standard 6-10kg",
                        "value" => '139'
                    ], [
                        "title" => "UPS Standard 10-15kg",
                        "value" => '140'
                    ], [
                        "title" => "UPS Standard 15-20kg",
                        "value" => '141'
                    ], [
                        "title" => "UPS Standard 20-30kg",
                        "value" => '142'
                    ], [
                        "title" => "UPS Standard to Access Point 0-3kg",
                        "value" => '194'
                    ], [
                        "title" => "UPS Standard to Access Point 3-6kg",
                        "value" => '195'
                    ], [
                        "title" => "UPS Standard to Access Point 6-10kg",
                        "value" => '196'
                    ], [
                        "title" => "UPS Standard to Access Point 10-15kg",
                        "value" => '197'
                    ], [
                        "title" => "UPS Standard to Access Point 15-20kg",
                        "value" => '198'
                    ], [
                        "title" => "UPS Standard 0-3kg Adult signature",
                        "value" => '260'
                    ], [
                        "title" => "UPS Standard 3-6kg Adult signature",
                        "value" => '262'
                    ], [
                        "title" => "UPS Standard 6-10kg Adult signature",
                        "value" => '263'
                    ], [
                        "title" => "UPS Standard 10-15kg Adult signature",
                        "value" => '264'
                    ], [
                        "title" => "UPS Standard 15-20kg Adult signature",
                        "value" => '265'
                    ], [
                        "title" => "UPS Standard 20-30kg Adult signature",
                        "value" => '266'
                    ], [
                        "title" => "UPS Standard 30-40kg Adult signature",
                        "value" => '267'
                    ], [
                        "title" => "UPS Standard 40-50kg Adult signature",
                        "value" => '268'
                    ], [
                        "title" => "UPS Standard 50-60kg Adult signature",
                        "value" => '269'
                    ], [
                        "title" => "UPS Standard 60-70kg Adult signature",
                        "value" => '270'
                    ], [
                        "title" => "DHL Express Domestic 0-1kg",
                        "value" => '819'
                    ], [
                        "title" => "DHL Express Domestic 1-2kg",
                        "value" => '820'
                    ], [
                        "title" => "DHL Express Domestic 2-3kg",
                        "value" => '821'
                    ], [
                        "title" => "DHL Express Domestic 3-4kg",
                        "value" => '822'
                    ], [
                        "title" => "DHL Express Domestic 4-5kg",
                        "value" => '823'
                    ], [
                        "title" => "DHL Express Domestic 5-6kg",
                        "value" => '824'
                    ], [
                        "title" => "DHL Express Domestic 6-7kg",
                        "value" => '825'
                    ], [
                        "title" => "DHL Express Domestic 7-8kg",
                        "value" => '826'
                    ], [
                        "title" => "DHL Express Domestic 8-9kg",
                        "value" => '827'
                    ], [
                        "title" => "DHL Express Domestic 9-10kg",
                        "value" => '828'
                    ], [
                        "title" => "DHL Express Domestic 10-11kg",
                        "value" => '829'
                    ], [
                        "title" => "DHL Express Domestic 11-12kg",
                        "value" => '830'
                    ], [
                        "title" => "DHL Express Domestic 12-13kg",
                        "value" => '831'
                    ], [
                        "title" => "DHL Express Domestic 13-14kg",
                        "value" => '832'
                    ], [
                        "title" => "DHL Express Domestic 14-15kg",
                        "value" => '833'
                    ], [
                        "title" => "DHL Express Domestic 15-16kg",
                        "value" => '834'
                    ], [
                        "title" => "DHL Express Domestic 16-17kg",
                        "value" => '835'
                    ], [
                        "title" => "DHL Express Domestic 17-18kg",
                        "value" => '836'
                    ], [
                        "title" => "DHL Express Domestic 18-19kg",
                        "value" => '837'
                    ], [
                        "title" => "DHL Express Domestic 19-20kg",
                        "value" => '838'
                    ], [
                        "title" => "DHL Express Domestic 20-21kg",
                        "value" => '839'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 0-1kg",
                        "value" => '889'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 1-2kg",
                        "value" => '890'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 2-3kg",
                        "value" => '891'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 3-4kg",
                        "value" => '892'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 4-5kg",
                        "value" => '893'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 5-6kg",
                        "value" => '894'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 6-7kg",
                        "value" => '895'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 7-8kg",
                        "value" => '896'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 8-9kg",
                        "value" => '897'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 9-10kg",
                        "value" => '898'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 10-11kg",
                        "value" => '899'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 11-12kg",
                        "value" => '900'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 12-13kg",
                        "value" => '901'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 13-14kg",
                        "value" => '902'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 14-15kg",
                        "value" => '903'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 15-16kg",
                        "value" => '904'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 16-17kg",
                        "value" => '905'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 17-18kg",
                        "value" => '906'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 18-19kg",
                        "value" => '907'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 19-20kg",
                        "value" => '908'
                    ], [
                        "title" => "DHL Express Domestic Premium 9 20-21kg",
                        "value" => '909'
                    ], [
                        "title" => "DHL Express Worldwide 4-5kg - incoterm DAP",
                        "value" => '2384'
                    ], [
                        "title" => "DHL Express Worldwide 5-6kg - incoterm DAP",
                        "value" => '2385'
                    ], [
                        "title" => "DHL Express Worldwide 6-7kg - incoterm DAP",
                        "value" => '2386'
                    ], [
                        "title" => "DHL Express Worldwide 7-8kg - incoterm DAP",
                        "value" => '2387'
                    ], [
                        "title" => "DHL Express Worldwide 8-9kg - incoterm DAP",
                        "value" => '2388'
                    ], [
                        "title" => "DHL Express Worldwide 9-10kg - incoterm DAP",
                        "value" => '2389'
                    ], [
                        "title" => "DHL Express Worldwide 10-11kg - incoterm DAP",
                        "value" => '2390'
                    ], [
                        "title" => "DHL Express Worldwide 11-12kg - incoterm DAP",
                        "value" => '2391'
                    ], [
                        "title" => "DHL Express Worldwide 12-13kg - incoterm DAP",
                        "value" => '2392'
                    ], [
                        "title" => "DHL Express Worldwide 13-14kg - incoterm DAP",
                        "value" => '2393'
                    ], [
                        "title" => "DHL Express Worldwide 14-15kg - incoterm DAP",
                        "value" => '2394'
                    ], [
                        "title" => "DHL Express Worldwide 0-1kg - incoterm DAP",
                        "value" => '2503'
                    ], [
                        "title" => "DHL Express Worldwide 1-2kg - incoterm DAP",
                        "value" => '2504'
                    ], [
                        "title" => "DHL Express Worldwide 2-3kg - incoterm DAP",
                        "value" => '2505'
                    ], [
                        "title" => "DHL Express Worldwide 3-4kg - incoterm DAP",
                        "value" => '2506'
                    ], [
                        "title" => "DHL Express Worldwide 17-18kg - incoterm DAP",
                        "value" => '2507'
                    ]
                ],
                'channel_based' => false,
                'locale_based' => true
            ],
        ]
    ],
];