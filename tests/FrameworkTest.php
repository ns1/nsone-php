<?php

namespace NSONE\Tests;

use NSONE\Client;

class FrameworkTest extends \PHPUnit_Framework_TestCase
{

    public function testClient()
    {
        $nsone = new Client();
        $this->assertTrue((bool)$nsone);
    }

}
