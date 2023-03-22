<?php

namespace Pam\api;

require_once 'RestAPIClient.php';
require_once dirname(__FILE__) . '/../cookies/CookieManager.php';

class TrackerAPI
{
    private $pamEndpoint;
    private $frontendDomain;
    

    public function __construct($pamEndpoint, $frontendDomain = "")
    {
        $this->pamEndpoint = rtrim($pamEndpoint, '/');
        $this->frontendDomain = rtrim($frontendDomain, '/');
    }
    
    public function postTracker($event, $databaseAlias, $data)
    {
        $cookieManager = "Pam\\cookies\\CookieManager";
        $origin = "";
        $sameSite = "None";
        $cookieSecure = true;
        $cookieHttpOnly = false;
        $referer = "";

        if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $origins = explode('://', $_SERVER['HTTP_ORIGIN']);
            if (sizeof($origins) > 1) {
                $origin = rtrim($origins[1]);
                $this->frontendDomain = $origin;
            }
        }
        echo $this->frontendDomain;
        if ($this->frontendDomain == "") {
            $sameSite = "Lax";
        }
        $cm = new $cookieManager($this->frontendDomain, $cookieSecure, $cookieHttpOnly, $sameSite);
        $contactId = $cm->getCookie("contact_id");
        $fbc = $cm->getCookie("_fbc");
        $fbp = $cm->getCookie("_fbp");
        $body = [
            "event" => $event,
            "form_fields" => [
                "_database"=> $databaseAlias,
            ]
        ];

        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            $body["platform"] = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $body["platform"] = "Pam PHP SDK";
        }
        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $referer = $_SERVER['HTTP_REFERER'];
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

        $url = "$this->pamEndpoint/trackers/events";
        $headers = [
            'Content-Type: application/json'
        ];
        
        $userAgent = $body["platform"];
        $jsonString = json_encode($body);
        $restClient = new RestClient($url, $headers, 'POST', $jsonString, $userAgent, $referer);
        $response = $restClient->sendRequest();
        $jsonResponse = json_decode($response);
        if (isset($jsonResponse->contact_id)) {
            $cm->setCookie("contact_id", $jsonResponse->contact_id);
        }
        return $jsonResponse;
    }
}