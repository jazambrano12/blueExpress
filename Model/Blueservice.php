<?php declare(strict_types=1);

namespace BlueExpress\Shipping\Model;

use Magento\Checkout\Model\Session as CheckoutSession;
use BlueExpress\Shipping\Helper\Data as HelperBX;
use Magento\Framework\HTTP\Client\Curl as Curl;
use Psr\Log\LoggerInterface as LoggerInterface;

class Blueservice
{
    /**
     *
     *
     * @var string
     */
    protected $_apiUrlGeo;

    /**
     *
     *
     * @var string
     */
    protected $_apiUrlPrice;

    /**
     *
     *
     * @var string
     */
    protected $_clientaccount;

    /**
     *
     *
     * @var string
     */
    protected $_usercode;

    /**
     *
     *
     * @var string
     */
    protected $_bxapiKey;

    /**
     *
     *
     * @var string
     */
    protected $_token;

    /**
     *
     *
     * @var string
     */
    protected $_webhook;

    /**
     *
     *
     * @var string
     */
    protected $_keywebhook;

    /**
     *
     *
     * @var HelperBX
     */
    protected $helperBX;

    /**
     *
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     *
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * Webservice constructor.
     * @param CheckoutSession $checkoutSession
     * @param HelperBX $helperBX
     * @param Curl $curl
     * @param LoggerInterface $logger
     */
    public function __construct(
        HelperBX $helperBX,
        Curl $curl,
        LoggerInterface $logger
    ) { 
        $this->curl           = $curl;
        $this->logger         = $logger;
        $this->_clientaccount = $helperBX->getClientAccount();
        $this->_usercode      = $helperBX->getUserCode();
        $this->_bxapiKey      = $helperBX->getBxapiKey();
        $this->_apiUrlGeo     = $helperBX->getBxapiGeo();
        $this->_apiUrlPrice   = $helperBX->getBxapiPrice();
        $this->_token         = $helperBX->getToken();
        $this->_webhook       = $helperBX->getWebHook();
        $this->_keywebhook    = $helperBX->getKeyWebHook();
    }

    /**
     * Funcion para el envio de la orden
     * @param mixed $datosParams
     * @return array
     */
    public function getBXOrder($datosParams)
    {
        $headers = [
            "Content-Type" => "application/json",
            "apikey" => "{$this->_keywebhook}"
        ];
        $this->curl->setHeaders($headers);
        $this->curl->post("{$this->_webhook}", json_encode($datosParams));
        $result = $this->curl->getBody();

        return $result;
    }

    /**
     * Funcion para buscar el costo del despacho
     * @param array $shippingParams
     * @return array
     */
    public function getBXCosto($shippingParams)
    {
        $this->logger->info('Information sent to api price',$shippingParams);
        $headers = [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
            "apikey" => "{$this->_bxapiKey}",
            "BX-TOKEN" => "{$this->_token}"
        ];
        $this->curl->setHeaders($headers);
        $this->curl->post("{$this->_apiUrlPrice}", json_encode($shippingParams));
        $result = $this->curl->getBody();
        
        return $result;
    }

    /**
     * Funcion para setear la comuna
     * @param string $shippingCity
     * @return array
     */
    public function getGeolocation($shippingCity)
    {
        $headers = [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
            "BX-CLIENT_ACCOUNT" => "{$this->_clientaccount}",
            "BX-USERCODE" => "{$this->_usercode}",
            "BX-TOKEN" => "{$this->_token}"
        ];
        $this->curl->setHeaders($headers);
        $this->curl->get("{$this->_apiUrlGeo}");

        $result = $this->curl->getBody();

        $tempData = str_replace("\\", "",$result);
	    $geolocation = json_decode($tempData, true);

        $dadosGeo = [];
        foreach($geolocation['data'][0]['states'] as $bxData){
            foreach($bxData['ciudades'] as $bxDataC){
                if(strtolower($bxDataC['name']) ==strtolower($shippingCity)){
                    $dadosGeo['regionCode']     = $bxData['code'];
                    $dadosGeo['cidadeName']     = $bxDataC['name'];
                    $dadosGeo['cidadeCode']     = $bxDataC['code'];
                    $dadosGeo['districtCode']   = $bxDataC['defaultDistrict'];
                }
            }
            if(array_key_exists('cidadeName',$dadosGeo) && $dadosGeo['cidadeName'] == ''){
                foreach($bxData['ciudades'] as $bxDataC){
                    foreach($bxDataC['districts'] as $bxDataD){
                        if(strtolower($bxDataD['name']) ==strtolower($shippingCity)){
                            $dadosGeo['regionCode']     = $bxData['code'];
                            $dadosGeo['cidadeName']     = $bxDataC['name'];
                            $dadosGeo['cidadeCode']     = $bxDataC['code'];
                            $dadosGeo['districtCode']   = $bxDataC['defaultDistrict'];
                        }
                    }
                }
            }
        }
          return $dadosGeo;
    }

    public function eliminarAcentos($cadena)
    {

        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª','É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê','Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î','Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô','Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û','Ñ', 'ñ', 'Ç', 'ç'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a','E', 'E', 'E', 'E', 'e', 'e', 'e', 'e','I', 'I', 'I', 'I', 'i', 'i', 'i', 'i','O', 'O', 'O', 'O', 'o', 'o', 'o', 'o','U', 'U', 'U', 'U', 'u', 'u', 'u', 'u','N', 'n', 'C', 'c'),
            $cadena
        );
        return $cadena;
    }
}
