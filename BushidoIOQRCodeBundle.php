<?php

namespace BushidoIO\QRCodeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use BushidoIO\QRCodeBundle\DependencyInjection\BushidoIOQRCodeExtension;

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
