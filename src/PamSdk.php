<?php
namespace Pam;

require_once 'api/TrackerAPI.php';

use Pam\api\TrackerAPI;

class PamSdk
{   
    
    private $pamEndpoint;
    private $trackerAPI;
    private $frontendDomain;

    public function __construct($pamEndpoint, $frontendDomain = "")
    {
        $this->pamEndpoint = rtrim($pamEndpoint, '/');
        $this->frontendDomain = rtrim($frontendDomain, '/');
        $this->trackerAPI = new TrackerAPI($this->pamEndpoint, $this->frontendDomain);

    }
    
    public function postTracker($event, $databaseAlias, $data)
    {
        return $this->trackerAPI->postTracker($event, $databaseAlias, $data);
    }
}