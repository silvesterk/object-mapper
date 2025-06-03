<?php

namespace Silvesterk\ObjectMapper;

use Silvesterk\ObjectMapper\Exception\ObjectMapperException;

class ObjectMapper
{
    /**
     * @template T
     * Maps properties from the source object to the target object.
     *
     * @param object $source The source object containing properties to map.
     * @param object<T> $target The target object where properties will be set.
     * @return T The target object with mapped values.
     * @throws ObjectMapperException If an error occurs during mapping.
     */
    public static function map(object $source, object $target)
    {
        $targetClone = clone $target;
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
