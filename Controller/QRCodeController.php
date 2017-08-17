<?php

namespace Cmobi\QRCodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class QRCodeController extends Controller
{
    protected $http_max_age;
    protected $https_max_age;
    
    public function __construct()
    {
    }
    
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        
        $options = $this->container->getParameter('bushidoio_qrcode');
        $this->http_max_age = $options['http_max_age'];
        $this->https_max_age = $options['https_max_age'];
    }
    
    /**
     * @Route(
     *     "/bushidoioqrcode/{text}.{_format}",
     *     name="bushidoio_qrcode_url",
     *     requirements={
     *         "text" = ".+"
     *     },
     *     defaults={
     *         "_format": "png"
     *     }
     * )
     * @Method("GET")
     * @param Request $request The Request object
     * @param string $text The text to be converted to a QRCode image
     * @return Response The response with the image
     */
    public function qrcodeAction(Request $request, $text = '')
    {
        $format = $request->getRequestFormat();

        $size = $request->query->get('size');
        if (is_null($size)) {
            $size = 3;
        } else {
            $size = intval($size);
        }
        
        $contentType = '';
        switch ($format) {
            case 'png':
                $contentType = 'image/png';
                break;
            //case 'jpg':
            //    $contentType = 'image/jpeg';
            //    break;
            default:
                $contentType = '';
                break;
        }
        
        if ($text === '' || $contentType === '' || !is_int($size) || $size < 1 || $size > 40) {
            throw new HttpException(400);
        }
        
        $qrCodeService = $this->get('bushidoio_qrcode');
        $qrCode = $qrCodeService->getQRCode(urldecode($text), $size, $format);
        $localFilePath = $qrCode['filePath'];
        
        try {
            $content = file_get_contents($localFilePath);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
        
        $response = new Response();
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', 'attachment;filename="' . urlencode($text) . '.' . $format . '"');
        $response->setContent($content);
        
        $response->setCache(
            array(
                'max_age'       => $this->http_max_age,
                's_maxage'      => $this->https_max_age,
                'private'       => false,
                'public'        => true,
            )
        );
        
        return $response;
    }
}
