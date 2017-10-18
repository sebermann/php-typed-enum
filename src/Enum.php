<?php

namespace TypedEnum;

use ReflectionClass;
use BadMethodCallException;
use UnexpectedValueException;

use TypedEnum\Traits\StringUtils;

abstract class Enum
{
    use StringUtils {
        startsWith as private;
        stringAfter as private;
        toConstantsCase as private;
    }

    /**
     * @var string[]
     */
    protected static $keyCaseCache = [];

    public function __call(string $name, array $arguments)
    {
        if (self::startsWith($name, 'is')) {
            $key = self::toKeyCase(self::stringAfter($name, 'is'));
            self::throwIfKeyInvalid($key);
            return $this->is(static::keyToValue($key));
        }

        throw new BadMethodCallException('Unknown method: ' . $name);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        if (self::startsWith($name, 'make')) {
            $key = self::toKeyCase(self::stringAfter($name, 'make'));
            self::throwIfKeyInvalid($key);
            return new static(static::keyToValue($key));
        }

        throw new BadMethodCallException('Unknown method: ' . $name);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getKey(): string
    {
        return static::valueToKey($this->value);
    }

    public static function getConstants(): array
    {
        if (!isset(static::$constantsCache[static::class])) {
            if (isset(static::$constants)) {
                static::$constantsCache[static::class] = static::$constants;
            } else {
                $reflector = new ReflectionClass(static::class);
                static::$constantsCache[static::class] = $reflector->getConstants();
            }
        }

        return static::$constantsCache[static::class];
    }

    public static function getKeys(): array
    {
        return array_keys(self::getConstants());
    }

    public static function getValues(): array
    {
        return array_values(self::getConstants());
    }

    public static function withKey(string $key): Enum
    {
        return new static(static::keyToValue($key));
    }

    public static function parse($argument): Enum
    {
        $value = null;

        $constants = self::getConstants();

        $key = mb_strtoupper($argument);
        if (isset($constants[$key])) {
            $value = static::keyToValue($key);
        }

        if (!$value) {
            if ($key = array_search($argument, $constants)) {
                $value = $constants[$key];
            }
        }

        if ($value) {
            return new static($value);
        }

        throw new UnexpectedValueException(
            'Argument could not be parsed to enum: ' . $argument
        );
    }

    public function equals(Enum ...$enums): bool
    {
        foreach ($enums as $enum) {
            if ($this == $enum) {
                return true;
            }
        }

        return false;
    }

    public static function hasKey(string $key): bool
    {
        return isset(self::getConstants()[mb_strtoupper($key)]);
    }

    protected static function throwIfKeyInvalid(string $key): void
    {
        if (!self::hasKey($key)) {
            throw new UnexpectedValueException('Unknown enum key: ' . $key);
        }
    }

    private static function toKeyCase(string $string): string
    {
        if (!isset(self::$keyCaseCache[$string])) {
            self::$keyCaseCache[$string] = self::toConstantsCase($string);
        }

        return self::$keyCaseCache[$string];
    }
}
