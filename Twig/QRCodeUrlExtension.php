<?php

namespace BushidoIO\QRCodeBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QRCodeUrlExtension extends \Twig_Extension implements ContainerAwareInterface
{
    /**
     * {@inheritdoc}
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('bushidoio_qrcode_url', array($this, 'bushidoIOQRCodeUrlFilter')),
            new \Twig_SimpleFilter('bushidoio_qrcode_base64', array($this, 'bushidoIOQRCodeBase64Filter'))
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'bushidoio_qrcode_url' => new \Twig_Function_Method($this, 'bushidoIOQRCodeUrlFunction'),
            'bushidoio_qrcode_base64' => new \Twig_Function_Method($this, 'bushidoIOQRCodeBase64Function')
        );
    }
    
    /**
     * Get the URL for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param string $format
     * @param int $size
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeUrlFilter($text = '', $format = 'png', $size = 3)
    {
        return $this->generateUrl($text, $format, $size);
    }
    
    /**
     * Get the Base64 data for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param string $format
     * @param int $size
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeBase64Filter($text = '', $format = 'png', $size = 3)
    {
        return $this->generateBase64($text, $format, $size);
    }
    
    /**
     * Get the URL for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param string $format
     * @param int $size
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeUrlFunction($text = '', $format = 'png', $size = 3)
    {
        return $this->generateUrl($text, $format, $size);
    }
    
    /**
     * Get the Base64 data for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param string $format
     * @param int $size
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeBase64Function($text = '', $format = 'png', $size = 3)
    {
        return $this->generateBase64($text, $format, $size);
    }
    
    private function generateUrl($text = '', $format = 'png', $size = 3)
    {
        $options = $this->container->getParameter('bushidoio_qrcode');
        $isAbsoluteUrl = $options['absolute_url'];
        
        $router = $this->container->get('router');
        $url = $router->generate(
            'bushidoio_qrcode_url',
            array(
                'text' => urlencode($text),
                'format' => $format,
                'size' => $size,
            ),
            $isAbsoluteUrl
        );

        return $url;
    }
    
    private function generateBase64($text = '', $format = 'png', $size = 3)
    {
        $service = $this->container->get('bushidoio_qrcode.service');
        $image = $service->getQRCodeBase64($text, $format, $size);

        return "data:image/$format;base64," . $image;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bushidoio_qrcode_url_extension';
    }
}
