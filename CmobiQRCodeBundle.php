<?php

namespace Cmobi\QRCodeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Cmobi\QRCodeBundle\DependencyInjection\BushidoIOQRCodeExtension;

class CmobiQRCodeBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new BushidoIOQRCodeExtension();
        }

        return $this->extension;
    }
}
