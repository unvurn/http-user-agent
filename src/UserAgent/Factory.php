<?php

namespace Unvurn\Http\UserAgent;

use Unvurn\Http\UserAgent;

class Factory
{
    private array $userAgents = [];

    public function create(string $userAgentString): UserAgent
    {
        if (array_key_exists($userAgentString, $this->userAgents)) {
            return $this->userAgents[$userAgentString];
        }

        $userAgent = new UserAgent($userAgentString);
        $this->userAgents[$userAgentString] = $userAgent;
        return $userAgent;
    }
}
