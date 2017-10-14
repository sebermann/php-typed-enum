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

    public function is(string ...$values): bool
    {
        foreach ($values as $value) {
            if ($this->value === $value) {
                return true;
            }
        }

        return false;
    }

    protected static function keyToValue(string $key): string
    {
        self::throwIfKeyInvalid($key);
        return self::getConstants()[$key];
    }

    protected static function valueToKey(string $value): string
    {
        self::throwIfValueInvalid($value);
        return array_search($value, self::getConstants());
    }

    protected static function throwIfValueInvalid(string $value): void
    {
        if (!in_array($value, self::getConstants(), true)) {
            throw new UnexpectedValueException('Unknown enum value: ' . $value);
        }
    }
}
