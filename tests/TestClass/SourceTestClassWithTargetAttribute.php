<?php

namespace Silvesterk\ObjectMapper\Tests\TestClass;

use Silvesterk\ObjectMapper\Attribute\Target;

class SourceTestClassWithTargetAttribute
{
    #[Target(propertyName: 'testPropInt')]
    public ?int $testPropWithAttribute;
}