<?php

namespace BushidoIO\QRCodeBundle\Service;

require_once __DIR__.'/../Lib/phpqrcode/qrlib.php';

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QRcodeService extends \BushidoIO\QRCodeBundle\Lib\phpqrcode\QRcode implements ContainerAwareInterface
{
    private $container;
    private $cacheable;
    private $cacheDir;
    private $logsDir;
    private $findBestMask;
    private $findFromRandom;
    private $defaultMask;
    private $pngMaximumSize;
    
    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->readConfiguration();
    }
    
    private function readConfiguration()
    {
        $options = $this->container->getParameter('bushidoio_qrcode');
        
        $this->cacheable = $options['cacheable'];
        $this->cacheDir = $options['cache_dir'];
        
        if (empty($this->cacheDir)) {
            $this->cacheDir = $this->container->getParameter("kernel.cache_dir") . DIRECTORY_SEPARATOR . 'qrcodes' . DIRECTORY_SEPARATOR;
        }
        if(!file_exists($this->cacheDir)){
            mkdir($this->cacheDir, 0777, true);
        }
        
        $this->logsDir = $options['logs_dir'];
        if (empty($this->logsDir)) {
            $this->logDir = $this->container->getParameter("kernel.logs_dir") . DIRECTORY_SEPARATOR . 'qrcodes' . DIRECTORY_SEPARATOR;
        }
        if(!file_exists($this->logDir)){
            mkdir($this->logDir, 0777, true);
        }
        
        $this->findBestMask = $options['find_best_mask'];
        $this->findFromRandom = $options['find_from_random'];
        $this->defaultMask = $options['default_mask'];
        $this->pngMaximumSize = $options['png_maximum_size'];
        
        // Use cache - more disk reads but less CPU power, masks and format
        // templates are stored there
        define('QR_CACHEABLE', $this->cacheable);
        // Used when QR_CACHEABLE === true
        define('QR_CACHE_DIR', $this->cacheDir);
        // Default error logs dir
        define('QR_LOG_DIR', $this->logDir);

        // If true, estimates best mask (spec. default, but extremally slow;
        // Set to false to significant performance boost but (propably) worst
        // quality code
        define('QR_FIND_BEST_MASK', $this->findBestMask);
        // If false, checks all masks available, otherwise value tells count of
        // masks need to be checked, mask id are got randomly
        define('QR_FIND_FROM_RANDOM', $this->findFromRandom);
        // When QR_FIND_BEST_MASK === false
        define('QR_DEFAULT_MASK', $this->defaultMask);

        // Maximum allowed png image width (in pixels), tune to make sure GD and
        // PHP can handle such big images
        define('QR_PNG_MAXIMUM_SIZE', $this->pngMaximumSize);
    }
    
    public function getQRCode($text, $format = 'png', $size = 3)
    {
        $result = array();
        
        $options = array('format' => $format, 'size' => $size);
        $fileName = $this->createFileName($text, $options);
        $path = $this->getPath($text, $format, $size);
        
        $result['fileName'] = $fileName;
        $result['filePath'] = $path;
        
        return $result;
    }
    
    public function getQRCodeBase64($text, $format = 'png', $size = 3)
    {
        $content = "";
        
        $path = $this->getPath($text, $format, $size);
        
        try {
            $content = file_get_contents($path);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
        
        return base64_encode($content);
    }
    
    private function getPath($text, $format, $size)
    {
        $options = array('format' => $format, 'size' => $size);
        $path = $this->cacheDir . $this->createFileName($text, $options);
        
        if (!file_exists($path)){
            $this->png($text, $path, QR_ECLEVEL_L, $size);
        }
        
        return $path;
    }
    
    private function createFileName($text, $options = null)
    {
        $format = $options['format'];
        $size = $options['size'];
        
        return urlencode($text . "_$size.$format");
    }
}
