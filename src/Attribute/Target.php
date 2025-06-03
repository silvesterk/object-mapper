<?php

namespace Silvesterk\ObjectMapper\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Target
{
    public function __construct(public string $propertyName)
    {
    }
}
