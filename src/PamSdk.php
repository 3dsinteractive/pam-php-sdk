<?php
namespace Pam;

require_once 'api/TrackerAPI.php';

use Pam\api\TrackerAPI;

class PamSdk
{   
    private $config = [];
    private $baseApi;
    private $frontendDomain = "";
    private $publicDBAlias = "default";
    private $trackerAPI;

    public function __construct($config)
    {
        foreach ($config as $key => $value) {
            switch ($key) {
                case "baseApi":
                    $this->baseApi = rtrim($value, '/');
                    break;
                case "publicDBAlias":
                    $this->publicDBAlias = $value;
                    break;
                case "frontendDomain":
                    $this->frontendDomain = rtrim($value, '/');
                    break;
            }
        }
        $this->trackerAPI = new TrackerAPI($this->baseApi, $this->publicDBAlias, $this->frontendDomain);

    }
    
    public function postTracker($event, $data)
    {
        return $this->trackerAPI->postTracker($event, $data);
    }
}