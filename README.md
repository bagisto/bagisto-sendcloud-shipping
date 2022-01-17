# Introduction

Bagisto Send Cloud Shipping add-on provide you a better way of shipping. Bagisto Send Cloud Shipping Extention has various built-in features such as – api rate, fixed rate, create sendcloud shipping, generate label.

- Provide SendCloud shipping for bagisto.
- You can also Apply your Custom Rate or rate from the api.
- You can setup fixed rate or api rate from the backend configuration settings.
- You can setup Free shipping from at the back end.
- Generate sendcloud shipping from the admin pannel(backend).
- Generate label for the recently created sendcloud shipment.


## Requirements:

- **Bagisto**: v1.3.2.

## Installation :
- Run the following command
```
composer require bagisto/bagisto-sendcloud-shipping
```

- Goto config/concord.php file and add following line under 'modules'
```php
\Webkul\SendcloudShipping\Providers\ModuleServiceProvider::class
```

- Run these commands below to complete the setup
```
composer dump-autoload
```

```
php artisan migrate
php artisan route:cache
php artisan config:cache
```

```
php artisan vendor:publish
```
-> Press 0 and then press enter to publish all assets and configurations.

> That's it, now just execute the project on your specified domain.
