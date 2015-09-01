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
            new \Twig_SimpleFunction('bushidoio_qrcode_url', array($this, 'bushidoIOQRCodeUrlFunction')),
            new \Twig_SimpleFunction('bushidoio_qrcode_base64', array($this, 'bushidoIOQRCodeBase64Function')),
        );
    }
    
    /**
     * Get the URL for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param int $size
     * @param string $format
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeUrlFilter($text = '', $size = 3, $format = 'png')
    {
        return $this->generateUrl($text, $size, $format);
    }
    
    /**
     * Get the Base64 data for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param int $size
     * @param string $format
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeBase64Filter($text = '', $size = 3, $format = 'png')
    {
        return $this->generateBase64($text, $size, $format);
    }
    
    /**
     * Get the URL for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param int $size
     * @param string $format
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeUrlFunction($text = '', $size = 3, $format = 'png')
    {
        return $this->generateUrl($text, $size, $format);
    }
    
    /**
     * Get the Base64 data for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param int $size
     * @param string $format
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeBase64Function($text = '', $size = 3, $format = 'png')
    {
        return $this->generateBase64($text, $size, $format);
    }
    
    private function generateUrl($text = '', $size = 3, $format)
    {
        $options = $this->container->getParameter('bushidoio_qrcode');
        $isAbsoluteUrl = $options['absolute_url'];
        
        $router = $this->container->get('router');
        //$text = urlencode($text);
        //$text = str_replace('.', '%2E', $text);
        //$text = str_replace('-', '%2D', $text);
        $url = $router->generate(
            'bushidoio_qrcode_url',
            array(
                'text' => $text,
                '_format' => $format,
                'size' => $size,
            ),
            $isAbsoluteUrl
        );

        return $url;
    }
    
    private function generateBase64($text = '', $size = 3, $format = 'png')
    {
        $service = $this->container->get('bushidoio_qrcode');
        $image = $service->getQRCodeBase64($text, $size, $format);

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
