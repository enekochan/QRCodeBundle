<?php

namespace BushidoIO\QRCodeBundle;

use BushidoIO\QRCodeBundle\DependencyInjection\BushidoIOQRCodeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BushidoIOQRCodeBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new BushidoIOQRCodeExtension();
        }

        return $this->extension;
    }
}
