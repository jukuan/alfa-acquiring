<?php

declare(strict_types=1);

namespace Tests;

use AlfaAcquiring\HttpClient\HttpRequestInterface;
use AlfaAcquiring\RbsClient;
use PHPUnit\Framework\TestCase;

class RbsClientTest extends TestCase
{
    public function testErrorMessages()
    {
        // Create a stub for the SomeClass class.
        $http = $this->createStub(HttpRequestInterface::class);
//        $http = $this->createStub(RbsClient::class);

        $http = $this->createMock(HttpRequestInterface::class);

        $http->expects($this->any())->method('hasError')->willReturn(true);
        $http->expects($this->any())->method('getErrorDetails')->willReturn('error message');

        $rbsClient = new RbsClient('test-login', 'pass', $http);
        $rbsClient->doMethod('test');

        self::assertEquals(null, $rbsClient->getResponse()->getErrorMessage());
    }
}
