<?php

namespace Unvurn\Http\Tests\Unit;

use Unvurn\Http\Tests\TestCase;
use Unvurn\Http\UserAgent;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

class UserAgentTest extends TestCase
{
    public function testUserAgent()
    {
        $userAgent = new UserAgent("aaa");
        assertInstanceOf(UserAgent::class, $userAgent);
    }

    public function testUserAgentWithEmptyVersion()
    {
        $userAgent = new UserAgent("aaa");
        assertNull($userAgent->productVersion("aaa"));
    }

    public function testUserAgentWithValidString()
    {
        $userAgent = new UserAgent("aaa/111");
        assertEquals("111", $userAgent->productVersion("aaa"));
    }

    public function testUserAgentWithValidString2()
    {
        $userAgent = new UserAgent("aaa/111 bbb/222");
        assertEquals("111", $userAgent->productVersion("aaa"));
        assertEquals("222", $userAgent->productVersion("bbb"));
        assertNull($userAgent->productVersion("ccc"));
    }

    public function testUserAgentWithUnityPlayerSample()
    {
        $userAgent = new UserAgent("UnityPlayer/2021.3.38f1 (UnityWebRequest/1.0, libcurl/8.5.0-DEV)");
        assertNotNull($userAgent);
        assertEquals("2021.3.38f1", $userAgent->productVersion("UnityPlayer"));
        assertEquals("UnityWebRequest/1.0, libcurl/8.5.0-DEV", $userAgent->product("UnityPlayer")->comment);
    }

    public function testUserAgentWithChromeOnMacSample()
    {
        $userAgent = new UserAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36");
        assertNotNull($userAgent);
        assertEquals("5.0", $userAgent->productVersion("Mozilla"));
        assertEquals("Macintosh; Intel Mac OS X 10_15_7", $userAgent->product("Mozilla")->comment);
        assertEquals("537.36", $userAgent->productVersion("AppleWebKit"));
        assertEquals("KHTML, like Gecko", $userAgent->product("AppleWebKit")->comment);
        assertEquals("126.0.0.0", $userAgent->productVersion("Chrome"));
        assertEquals("537.36", $userAgent->productVersion("Safari"));
    }

    public function testUserAgentWithChromeOnMacAndNestedCommentSample()
    {
        $userAgent = new UserAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7 (nested comment is here) and (another one)) AppleWebKit/537.36 (KHTML, (nested comment 2) like Gecko) Chrome/126.0.0.0 Safari/537.36");
        assertNotNull($userAgent);
        assertEquals("5.0", $userAgent->productVersion("Mozilla"));
        assertEquals("Macintosh; Intel Mac OS X 10_15_7 (nested comment is here) and (another one)", $userAgent->product("Mozilla")->comment);
        assertEquals("537.36", $userAgent->productVersion("AppleWebKit"));
        assertEquals("KHTML, (nested comment 2) like Gecko", $userAgent->product("AppleWebKit")->comment);
        assertEquals("126.0.0.0", $userAgent->productVersion("Chrome"));
        assertEquals("537.36", $userAgent->productVersion("Safari"));
    }
}
