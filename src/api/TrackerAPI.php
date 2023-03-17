<?php

namespace Pam\api;

require_once 'RestAPIClient.php';

class TrackerAPI
{
    private $pamEndpoint;
    

    public function __construct($pamEndpoint)
    {
        $this->pamEndpoint = rtrim($pamEndpoint, '/');
       
    }
    
    public function postTracker($event, $databaseAlias, $data)
    {
        $body = [
            "event"=>$event,
            "platform" => "Pam PHP SDK",
            "form_fields" => [
                "_database"=> $databaseAlias,
            ]
        ];

        foreach ($data as $key => $value) {
            $body["form_fields"][$key] = $value;
        }

        $url = "$this->pamEndpoint/trackers/events";
        $headers = [
            'Content-Type: application/json'
        ];
        $jsonString = json_encode($body);
        $restClient = new RestClient($url, $headers, 'POST', $jsonString);
        $response = $restClient->sendRequest();
        $jsonResponse = json_decode($response);
        return $jsonResponse;
    }
}