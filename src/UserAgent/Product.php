<?php

namespace Unvurn\Http\UserAgent;

readonly class Product
{
    public function __construct(
        public ?string $version,
        public ?string $comment,
    )
    {
    }
}
