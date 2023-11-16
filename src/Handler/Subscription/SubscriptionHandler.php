<?php

namespace Handler\Subscription;

use Core\Application\Services\AdminService;
use Exception;
use Handler\BaseHandler;
use SimpleXMLElement;
use Utils\Response\Response;
use Utils\Http\HttpStatusCode;

class SubscriptionHandler extends BaseHandler
{
    protected static SubscriptionHandler $instance;
    protected AdminService $service;

    private function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }

    public static function getInstance(AdminService $adminService): SubscriptionHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $adminService
            );
        }
        return self::$instance;
    }

    protected function post($params = null): void
    {
        try {
            if (isset($params['paymentValue'])){
                $request = '<soapenv:Envelope
                            xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                            xmlns:tns="http://service.Bondflix.org/">
                            <soapenv:Header/>
                            <soapenv:Body>
                                <tns:processPayment>
                                    <userId>' . $_SESSION['user_id'] . '</userId>
                                    <paymentValue>' . $params['paymentValue'] . '</paymentValue>
                                </tns:processPayment>
                            </soapenv:Body>
                        </soapenv:Envelope>';

                $headersData = array(
                    "Content-Type:text/xml;charset=\"utf-8\"",
                    'api-key:'. $_ENV['APP_API_KEY'],
                );

                $url = $_ENV["SOAP_CLIENT_URL"];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headersData);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $responseData = curl_exec($ch);
                $pattern = '/<return>(.*?)<\/return>/';

                if (preg_match($pattern, $responseData, $matches)) {
                    $returnValue = $matches[1];
                } else {
                    $returnValue = false;
                }

                $response = new Response(true, 200, "payment value", $returnValue);
            } else {
                $response = new Response(true, 200, "parameter error", $params);
            }
            $response->encode_to_JSON();
            return;
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Request failed " . $e->getMessage(), false);
            $response->encode_to_JSON();
            return;
        }
    }
}