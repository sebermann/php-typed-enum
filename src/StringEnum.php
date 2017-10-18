<?php

namespace TypedEnum;

use JsonSerializable;
use UnexpectedValueException;

abstract class StringEnum extends Enum implements JsonSerializable
{
    /**
     * @var string[]
     */
    protected static $constantsCache = [];

    /**
     * @var string
     */
    protected $value;

    public function __construct(string $value)
    {
        self::throwIfValueInvalid($value);

        $this->value = $value;
    }

    public static function make(string $value): StringEnum
    {
        return new static($value);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function is(string ...$values): bool
    {
        foreach ($values as $value) {
            if ($this->value === $value) {
                return true;
            }
        }

        return false;
    }

    public static function keyToValue(string $key): string
    {
        $key = mb_strtoupper($key);
        self::throwIfKeyInvalid($key);
        return self::getConstants()[$key];
    }

    public static function valueToKey(string $value): string
    {
        self::throwIfValueInvalid($value);
        return array_search($value, self::getConstants());
    }

    public static function has(string $value): bool
    {
        return in_array($value, self::getConstants(), true);
    }

    protected static function throwIfValueInvalid(string $value): void
    {
        if (!self::has($value)) {
            throw new UnexpectedValueException('Unknown enum value: ' . $value);
        }
    }
}
