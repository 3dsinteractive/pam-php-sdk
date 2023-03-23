<?php

namespace Pam\api;

require_once 'RestAPIClient.php';
require_once dirname(__FILE__) . '/../cookies/CookieManager.php';

class TrackerAPI
{
    private $baseApi;
    private $publicDBAlias;
    

    public function __construct($baseApi, $publicDBAlias)
    {
        $this->baseApi = rtrim($baseApi, '/');
        $this->publicDBAlias = $publicDBAlias;
    }
    
    public function postTracker($event, $data)
    {
        $cookieManager = "Pam\\cookies\\CookieManager";
        $host = "";
        $sameSite = "Lax";
        $cookieSecure = true;
        $cookieHttpOnly = false;
        $referer = "";

        if (array_key_exists('HTTP_HOST', $_SERVER)) {
            $host = $_SERVER['HTTP_HOST'];
        }
        $cm = new $cookieManager($host, $cookieSecure, $cookieHttpOnly, $sameSite);
        $contactId = $cm->getCookie("contact_id");
        $fbc = $cm->getCookie("_fbc");
        $fbp = $cm->getCookie("_fbp");
        $url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $body = [
            "event" => $event,
            "platform" => "PHP SDK",
            "page_url" => $url,
            "form_fields" => [
                "_database"=> $this->publicDBAlias,
            ]
        ];
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $referer = $_SERVER['HTTP_REFERER'];
            $body["page_referrer"] = $referer;
        }
        if (isset($contactId)) {
            $body["form_fields"]['_contact_id'] = $contactId;
        }
        if (isset($fbc)) {
            $body["form_fields"]['_fbc'] = $fbc;
        }
        if (isset($fbp)) {
            $body["form_fields"]['_fbp'] = $fbp;
        }
        foreach ($data as $key => $value) {
            if ($key == 'page_url' || $key == 'page_title') {
                $body[$key] = $value;
            } else {
                $body["form_fields"][$key] = $value;
            }
        }

        $url = "$this->baseApi/trackers/events";
        $headers = [
            'Content-Type: application/json'
        ];
        
        $jsonString = json_encode($body);
        $cookiesString = $cm->buildCookiesString();
        $restClient = new RestClient($url, $headers, 'POST', $jsonString, $userAgent, $referer, $cookiesString);
        $response = $restClient->sendRequest();
        $jsonResponse = json_decode($response);
        
        $cm->setCookieFromCurl($response);
        if (isset($jsonResponse->contact_id)) {
            $cm->setCookie("contact_id", $jsonResponse->contact_id);
        }
        return $jsonResponse;
    }
}