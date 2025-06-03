
# ObjectMapper

A PHP library for mapping properties between objects with support for attributes and type safety.

## Features

- Map properties from a source object to a target object.
- Support for custom property mapping using attributes.
- Handles uninitialized properties gracefully.
- Throws exceptions for type mismatches or mapping errors.

## Requirements

- PHP 8.1 or higher
- Composer

## Installation

Install the library using Composer:

```bash
composer require silvesterk/object-mapper
```

## Usage

### Basic Mapping

```php
use Silvesterk\ObjectMapper\ObjectMapper;
use Silvesterk\ObjectMapper\Tests\TestClass\SourceTestClassWithNoAttributes;
use Silvesterk\ObjectMapper\Tests\TestClass\DestinationTestClass;

$source = new SourceTestClass();
$source->testProp = 42;

$destination = new DestinationTestClass();
$mappedObject = ObjectMapper::map($source, $destination);

echo $mappedObject->testProp; // Outputs: 42
```

### Mapping with Attributes

Use attributes to map properties with different names between source and target objects.

```php
use Silvesterk\ObjectMapper\ObjectMapper;
use Silvesterk\ObjectMapper\Tests\TestClass\SourceTestClassWithTargetAttribute;
use Silvesterk\ObjectMapper\Tests\TestClass\DestinationTestClass;

class SourceTestClassWithTargetAttribute
{
    #[Target(propertyName: 'differentProp')]
    public ?int $testProp = null;
}


$source = new SourceTestClassWithTargetAttribute();
$source->testProp = 42;

$destination = new DestinationTestClass();
$mappedObject = ObjectMapper::map($source, $destination);

echo $mappedObject->differentProp; // Outputs: 42
```

### Handling Type Mismatches

If a property type mismatch occurs, an `ObjectMapperException` is thrown.

```php
use Silvesterk\ObjectMapper\ObjectMapper;
use Silvesterk\ObjectMapper\Exception\ObjectMapperException;
use Silvesterk\ObjectMapper\Tests\TestClass\SourceTestClassWithWrongDataType;
use Silvesterk\ObjectMapper\Tests\TestClass\DestinationTestClass;


$source = new SourceTestClassWithWrongDataType();
$source->testPropInt = 'Invalid Type';

$destination = new DestinationTestClass();

try {
    ObjectMapper::map($source, $destination);
} catch (ObjectMapperException $e) {
    echo $e->getMessage(); // Outputs the error message
}
```

## Testing

Run the tests using PHPUnit:

```bash
vendor/bin/phpunit
```

## Contributing

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes and push the branch.
4. Open a pull request.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.