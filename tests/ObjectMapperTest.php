<?php

namespace Silvesterk\ObjectMapper\Tests;

use PHPUnit\Framework\TestCase;
use Silvesterk\ObjectMapper\Exception\ObjectMapperException;
use Silvesterk\ObjectMapper\ObjectMapper;
use Silvesterk\ObjectMapper\Tests\TestClass\DestinationTestClass;
use Silvesterk\ObjectMapper\Tests\TestClass\SourceTestClassWithNoAttributes;
use Silvesterk\ObjectMapper\Tests\TestClass\SourceTestClassWithTargetAttribute;
use Silvesterk\ObjectMapper\Tests\TestClass\SourceTestClassWithWrongDataType;

class ObjectMapperTest extends TestCase
{
    /**
     * @throws ObjectMapperException
     */
    public function testMapObjectWithRightTypesAndNoAttributes()
    {
        $source = new SourceTestClassWithNoAttributes();
        $source->testPropInt = 43;
        $destination = new DestinationTestClass();

        $changedDestination = ObjectMapper::map($source, $destination);
        $this->assertInstanceOf(DestinationTestClass::class, $changedDestination);
        $this->assertEquals(43, $changedDestination->testPropInt);
        $this->assertNull($changedDestination->testPropString);
    }

    public function testMapObjectWithRightTypesAndWithTargetAttribute()
    {
        $source = new SourceTestClassWithTargetAttribute();
        $source->testPropWithAttribute = 43;
        $destination = new DestinationTestClass();

        $changedDestination = ObjectMapper::map($source, $destination);
        $this->assertInstanceOf(DestinationTestClass::class, $changedDestination);
        $this->assertEquals(43, $changedDestination->testPropInt);
        $this->assertNull($changedDestination->testPropString);
    }

    public function testMapObjectWithWrongType()
    {
        $source = new SourceTestClassWithWrongDataType();
        $source->testPropInt = 'THIS IS A STRING, NOT AN INT';
        $destination = new DestinationTestClass();

        $this->expectException(ObjectMapperException::class);

        $changedDestination = ObjectMapper::map($source, $destination);
    }
}