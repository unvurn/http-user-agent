<?php

namespace Unvurn\Http;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class HttpUserAgentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Request::macro('getUserAgent', function () {
            /** @var $this Request */
            return UserAgent::create($this->userAgent());
        });
    }
}
