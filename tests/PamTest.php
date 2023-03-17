<?php
require_once dirname(__FILE__) . '/../src/PamSdk.php';

use Pam\PamSdk;
use PHPUnit\Framework\TestCase;

class PamTest extends TestCase
{
    public function testSendEvent()
    {
        $sdk = new PamSdk("https://stgx.pams.ai");
        $eventData = [
            'price' => 300, 
            'product_id'=> "ABC12345"
        ];
        
        $result = $sdk->postTracker("buy_product","win-test", $eventData);

        fwrite(STDERR, print_r($result, TRUE));

        $this->assertArrayHasKey('contact_id', (array) $result );
        
    }
}
