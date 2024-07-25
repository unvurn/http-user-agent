<?php

namespace Unvurn\Http\Tests\Feature;

use Illuminate\Http\Request;
use Unvurn\Http\HttpUserAgentServiceProvider;
use Unvurn\Http\Tests\TestCase;

class RequestGetUserAgentTest extends TestCase
{
    public function testSimple() {
        $request = new Request();
        $request->headers->set('User-Agent', 'foo/1.0');

        $this->assertNotNull($request);
        $this->assertEquals('foo/1.0', $request->userAgent());

        $userAgent = $request->getUserAgent();
        $this->assertEquals('1.0', $userAgent->productVersion("foo"));
        $this->assertNull($userAgent->productComment("foo"));
    }

    protected function getPackageProviders($app): array
    {
        return [
            HttpUserAgentServiceProvider::class,
        ];
    }
}
