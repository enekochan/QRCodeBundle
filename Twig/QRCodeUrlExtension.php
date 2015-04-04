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
            new \Twig_SimpleFilter('bushido_ioqr_code_url', array($this, 'bushidoIOQRCodeUrlFilter')),
            new \Twig_SimpleFilter('bushido_ioqr_code_base64', array($this, 'bushidoIOQRCodeBase64Filter'))
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'bushido_ioqr_code_url' => new \Twig_Function_Method($this, 'bushidoIOQRCodeUrlFunction'),
            'bushido_ioqr_code_base64' => new \Twig_Function_Method($this, 'bushidoIOQRCodeBase64Function')
        );
    }
    
    /**
     * Get the URL for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param string $_format
     * @param int $size
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeUrlFilter($text = '', $_format = 'png', $size = 3)
    {
        return $this->generateUrl($text, $_format, $size);
    }
    
    /**
     * Get the Base64 data for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param string $_format
     * @param int $size
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeBase64Filter($text = '', $_format = 'png', $size = 3)
    {
        return $this->generateBase64($text, $_format, $size);
    }
    
    /**
     * Get the URL for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param string $_format
     * @param int $size
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeUrlFunction($text = '', $_format = 'png', $size = 3)
    {
        return $this->generateUrl($text, $_format, $size);
    }
    
    /**
     * Get the Base64 data for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param string $_format
     * @param int $size
     * @return string The URL for the QRCode
     */
    public function bushidoIOQRCodeBase64Function($text = '', $_format = 'png', $size = 3)
    {
        return $this->generateBase64($text, $_format, $size);
    }
    
    private function generateUrl($text = '', $_format = 'png', $size = 3)
    {
        $options = $this->container->getParameter('bushido_ioqr_code');
        $fullUrl = $options['full_url'];
        
        $router = $this->container->get('router');
        $url = $router->generate('bushido_ioqr_code_url', array(
            'text' => urlencode($text),
            '_format' => $_format,
            'size' => $size,
        ), $fullUrl);

        return $url;
    }
    
    private function generateBase64($text = '', $_format = 'png', $size = 3)
    {
        return "data:image/$_format;base64," . $this->container->get('bushido_ioqr_code.service')->getQRCodeBase64($text, $_format, $size);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bushido_ioqr_code_url_extension';
    }
}
