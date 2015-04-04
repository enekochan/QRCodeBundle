BushidoIOQRCodeBundle
=====================

The BushidoIOQRCodeBundle adds QRCode generation support in Symfony2.

Features included:

- URL and BASE64 encoded image generation
- Twig filter and function support
- Cacheable images for less CPU usage
- Configurable cache expiration age for both http and https
- Cache and logs path can be located inside or outside Symfony2 app folder tree
- Full path or relative URL generation
- PNG maximun image size definition (default 1024 pixels)
- Configurable find mask setup (best mask, random mask and default mask value)

Installation
------------
### Step 1: Composer
Add the following require line to the `composer.json` file:
``` json
{
    "require": {
        "bushidoio/qrcode-bundle": "dev-master"
    }
}
```
And actually install it in your project using Composer:
``` bash
php composer.phar install
```
You can also do this in one step with this command:
``` bash
$ php composer.phar require bushidoio/qrcode-bundle "dev-master"
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new BushidoIO\QRCodeBundle\BushidoIOQRCodeBundle(),
    );
}
```

Configuration
-------------
You can configure some options in `app/config/config.yml`. Those are the default
values:

``` yaml
bushidoio_qrcode:
    cacheable: true
    cache_dir: ~
    logs_dir: ~
    find_best_mask: true
    find_from_random: false
    default_mask: 2
    png_maximun_size: 1024
    full_url: true
    http_max_age: 600
    https_max_age: 600
```

Usage examples
--------------
You can use Twig functions or filtes to create URL or BASE64 encoded images.
If no parameters are used the default options for the image are PNG and size 3
(87x87 pixels).

``` html
<img src="{{ bushidoio_qrcode_url('Text to encode') }}" />

<img src="{{ bushidoio_qrcode_url('Text to encode', 'png', 5) }}" />

<img src="{{ bushidoio_qrcode_base64('Text to encode') }}" />

<img src="{{ bushidoio_qrcode_base64('Text to encode', 'png', 5) }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_url }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_url('png', 5) }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_base64 }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_base64('png', 5) }}" />
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    LICENSE

This bundle uses PHP QR Code encoder under the hood. PHP QR Code is distributed
under LGPL 3. Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
See complete license in:

    Lib/phpqrcode/LICENSE

PHP QR Code encoder is a PHP implementation of QR Code 2-D barcode generator.
It is pure-php LGPL-licensed implementation based on C libqrencode by Kentaro
Fukuchi.
