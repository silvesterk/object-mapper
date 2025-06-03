<?php

namespace Silvesterk\ObjectMapper;

use Silvesterk\ObjectMapper\Exception\ObjectMapperException;

class ObjectMapper
{
    /**
     * @template T
     * Maps properties from the source object to the target object. Creating a new instance of the target object.
     *
     * @param object $source The source object containing properties to map.
     * @param object<T> $target The target object where properties will be set.
     * @return T The target object with mapped values.
     * @throws ObjectMapperException If an error occurs during mapping.
     */
    public static function mapImmutable(object $source, object $target)
    {
        return self::doMapping($source, $target, true);
    }

    /**
     * @template T
     * Maps properties from the source object to the target object.
     *
     * @param object $source The source object containing properties to map.
     * @param object<T> $target The target object where properties will be set.
     * @return void
     * @throws ObjectMapperException If an error occurs during mapping.
     */
    public static function map(object $source, object $target): void
    {
        self::doMapping($source, $target, false);
    }

    /**
     * @template T
     * Maps properties from the source object to the target object. Creating a new instance of the target object.
     *
     * @param object $source The source object containing properties to map.
     * @param object<T> $target The target object where properties will be set.
     * @param bool $immutable If true, a new instance of the target object will be created; otherwise, the existing instance will be modified.
     * @return T The target object with mapped values.
     * @throws ObjectMapperException If an error occurs during mapping.
     */
    private static function doMapping(object $source, object $target, bool $immutable = true): object
    {
        if ($immutable) {
            $targetClone = clone $target;
        } else {
            $targetClone = $target;
        }
        try {
            $sourceReflection = new \ReflectionClass($source::class);
            $sourceProperties = $sourceReflection->getProperties();
            $targetReflection = new \ReflectionClass($targetClone::class);
            foreach ($sourceProperties as $sourceProperty) {
                $field = $sourceProperty->getName();
                $attributes = $sourceProperty->getAttributes(Attribute\Target::class);
                if (!empty($attributes)) {
                    /** @var Attribute\Target $targetAttribute */
                    $targetAttribute = $attributes[0]->newInstance();
                    $field = $targetAttribute->propertyName;
                }

                if (!$sourceProperty->isInitialized($source)) {
                    continue;
                }
                try {
                    $targetProperty = $targetReflection->getProperty($field);
                } catch (\Exception) {
                    // If the property does not exist, skip it
                    continue;
                }
                $targetProperty->setValue($targetClone, $sourceProperty->getValue($source));
            }
        } catch (\Throwable $exception) {
            throw new ObjectMapperException($exception->getMessage(), $exception->getCode(), $exception);
        }
        // Return the target object with mapped values
        return $targetClone;
    }
}
