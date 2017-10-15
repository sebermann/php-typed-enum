# PHP Typed Enum

[![Build Status](https://travis-ci.org/sebermann/php-typed-enum.svg?branch=master)](https://travis-ci.org/sebermann/php-typed-enum)
[![Coverage Status](https://coveralls.io/repos/github/sebermann/php-typed-enum/badge.svg?branch=master)](https://coveralls.io/github/sebermann/php-typed-enum?branch=master)

This is an enum library for PHP 7.1+ with support for strict typing.

## Definition

Define a class extending either `TypedEnum\IntegerEnum` or `TypedEnum\StringEnum`
with each allowed value as a class constant. The constant name must conform to
[PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
and be all upper case with underscore separators.

```php
final class Color extends \TypedEnum\IntegerEnum
{
    const YELLOW = 1;
    const PURPLE = 2;
    const ORANGE = 3;
}
```

```php
final class Country extends \TypedEnum\StringEnum
{
    const ISRAEL = 'isr';
    const FRANCE = 'fra';
    const POLAND = 'pol';
}
```

These constants will be accessed using reflection and subsequently be cached in a
static property. If you prefer you can define them yourself by adding a `$constants`
property.

```php
protected static $constants = [
    'YELLOW' => self::YELLOW,
    'PURPLE' => self::PURPLE,
    'ORANGE' => self::ORANGE
];
```

All methods treat key arguments as case-insensitive, so you can pass `'YELLOW'`
as well as `'yellow'`.

## Static Usage

For basic usage you can use the class constants and the `getKey()` and `getValue()`
static methods.

```php
$color = Color::PURPLE;
```

```php
if ($color === Color::PURPLE) {
    return true;
}
```

```php
$purpleValue = Color::getValue('purple');  // Returns 2
$purpleKey = Color::getKey($purpleValue);  // Returns 'PURPLE'
```

## Dynamic Usage

Creating enum instances allows the usage of enum classes as type declarations.

```php
public function setColor(Color $color)
{
    $this->color = $color;
}
```

It can also prevent mix-up errors when different enums share keys or values.

```php
Color::ORANGE === Fruit::ORANGE;  // Could erroneously be true
Color::makeOrange()->sameAs(Fruit::makeOrange());  // Reliably false
```

### Creation

To create an enum instance you can either use the class constant or a magic method.

```php
$color = new Color(Color::YELLOW);
$color = Color::make(Color::YELLOW);
$color = Color::makeYellow();
```

If you only have a key you have to either convert it using `getValue()` or use the
`withKey()` method.

```php
$color = Color::make(Color::getValue('yellow'));
$color = Color::withKey('yellow');
```

If the input could either be a key or a value you can use the `parse()` method.
For example the query strings

* `?color=2`
* `?color=purple`

can both be handled in the same way.

```php
$color = Color::parse($queryParams['color']);
```

### Evaluation

To evaluate an enum instance you can either use the class constant or a magic method.

```php
$color->is(Color::YELLOW);
$color->isYellow();
```

Comparisons with other enum instances are done using the `sameAs()` method.

```php
if ($car->color->sameAs($preferences->color)) {
    return $car;
}
```

Both `is()` and `sameAs()` are variadic, so you can pass multiple arguments and
only one must be a match.

### Serialization

When cast to string an enum instance returns its value as a string.

When serialized as JSON an enum instance returns its value as its respective type.

## Tests

```
$ vendor/bin/phpunit
```
