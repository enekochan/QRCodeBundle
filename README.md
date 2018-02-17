BushidoIOQRCodeBundle
=====================

The BushidoIOQRCodeBundle adds QRCode generation support in Symfony2.

Features included:

- URL and BASE64 encoded image generation
- Twig filter and function support
- Cacheable images for less CPU usage
- Configurable cache expiration age for both http and https
- Cache and logs path can be located inside or outside Symfony2 app folder tree
- Absolute or relative URL generation
- PNG maximum image size definition (default 1024 pixels)
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

## Step 3: Enable the route

Add this to your routing configuration in `app/config/routing.yml`:

``` yaml
bushidoio_qrcode:
    resource: "@BushidoIOQRCodeBundle/Controller/"
    type:     annotation
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
    png_maximum_size: 1024
    absolute_url: true
    http_max_age: 600
    https_max_age: 600
```

Usage examples
--------------
You can use Twig functions or filtes to create URL or BASE64 encoded images.
If no parameters are used the default options for the image are size 3 (87x87
pixels) and PNG format.

``` html
<img src="{{ bushidoio_qrcode_url('Text to encode') }}" />

<img src="{{ bushidoio_qrcode_url('Text to encode', 5) }}" />

<img src="{{ bushidoio_qrcode_url('Text to encode', 5, 'png') }}" />

<img src="{{ bushidoio_qrcode_base64('Text to encode') }}" />

<img src="{{ bushidoio_qrcode_base64('Text to encode', 5) }}" />

<img src="{{ bushidoio_qrcode_base64('Text to encode', 5, 'png') }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_url }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_url(5) }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_url(5, 'png') }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_base64 }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_base64(5) }}" />

<img src="{{ 'Text to encode'|bushidoio_qrcode_base64(5, 'png') }}" />
```
