<?php

namespace Webkul\SendcloudShipping\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\SendcloudShipping\Models\Sendcloud::class,
    ];
}

