<?php
namespace Pam;

require_once 'api/TrackerAPI.php';

use Pam\api\TrackerAPI;

class PamSdk
{   
    
    private $pamEndpoint;
    private $trackerAPI;

    public function __construct($pamEndpoint)
    {
        $this->pamEndpoint = rtrim($pamEndpoint, '/');
        $this->trackerAPI = new TrackerAPI($this->pamEndpoint);

    }
    
    public function postTracker($event, $databaseAlias, $data)
    {
        return $this->trackerAPI->postTracker($event, $databaseAlias, $data);
    }
}