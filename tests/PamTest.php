<?php
require_once dirname(__FILE__) . '/../src/PamSdk.php';

use Pam\PamSdk;
use PHPUnit\Framework\TestCase;

class PamTest extends TestCase
{
    public function testSendEvent()
    {
        $pamConfig = [
            "baseApi" => "https://stgx.pams.ai",
            "publicDBAlias" => "win-test"
        ];
        $sdk = new PamSdk($pamConfig);
        $eventData = [
            'price' => 300, 
            'product_id'=> "ABC12345"
        ];
        
        $result = $sdk->postTracker("buy_product", $eventData);

        fwrite(STDERR, print_r($result, TRUE));

        $this->assertArrayHasKey('contact_id', (array) $result );
        
    }
}
