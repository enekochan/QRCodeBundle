<?php

namespace BushidoIO\QRCodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QRCodeController extends Controller
{
    protected $max_age;
    protected $s_max_age;
    
    public function __construct()
    {
    }
    
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        
        $options = $this->container->getParameter('bushido_ioqr_code');
        $this->max_age = $options['http_max_age'];
        $this->s_max_age = $options['http_s_max_age'];
    }
    
    public function qrcodeAction(Request $request, $text = '', $_format = 'png')
    {
        $size = $request->query->get('size');
        if (is_null($size)) {
            $size = 3;
        } else {
            $size = intval($size);
        }
        
        $contentType = "";
        switch ($_format) {
            case "png":
                $contentType = "image/png";
                break;
            //case "jpg":
            //    $contentType = "image/jpeg";
            //    break;
            default:
                $contentType = "";
                break;
        }
        
        if ($text === "" || $contentType === "" || !is_int($size) || $size < 1 || $size > 40) {
            throw new HttpException(400);
        }
        
        $qrCodeService = $this->get('bushido_ioqr_code.service');
        $qrCode = $qrCodeService->getQRCode(urldecode($text), $_format, $size);
        $localFilePath = $qrCode['filePath'];
        
        try {
            $content = file_get_contents($localFilePath);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
        
        $response = new Response();
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', 'attachment;filename="' . urlencode($text) . '.' . $_format . '"');
        $response->setContent($content);
        
        $response->setCache(array(
            'max_age'       => $this->max_age,
            's_maxage'      => $this->s_max_age,
            'private'       => false,
            'public'        => true,
        ));
        
        return $response;
    }
}
