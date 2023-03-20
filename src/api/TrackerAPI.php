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
        $cm = new $cookieManager($this->frontendDomain, true, true, 'None');
        $contactId = $cm->getCookie("contact_id");

        $body = [
            "event" => $event,
            "platform" => "Pam PHP SDK",
            "form_fields" => [
                "_database"=> $databaseAlias,
            ]
        ];

        if (isset($contactId)) {
            $body["form_fields"]['_contact_id'] = $contactId;
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
        $jsonString = json_encode($body);
        $restClient = new RestClient($url, $headers, 'POST', $jsonString);
        $response = $restClient->sendRequest();
        $jsonResponse = json_decode($response);
        if (isset($jsonResponse->contact_id)) {
            $cm->setCookie("contact_id", $jsonResponse->contact_id);
        }
        return $jsonResponse;
    }
}