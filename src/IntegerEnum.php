<?php

namespace TypedEnum;

use JsonSerializable;
use UnexpectedValueException;

abstract class IntegerEnum extends Enum implements JsonSerializable
{
    /**
     * @var int[]
     */
    protected static $constantsCache = [];

    /**
     * @var int
     */
    protected $value;

    public function __construct(int $value)
    {
        self::throwIfValueInvalid($value);

        $this->value = $value;
    }

    public static function make(int $value): IntegerEnum
    {
        return new static($value);
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function is(int ...$values): bool
    {
        foreach ($values as $value) {
            if ($this->value === $value) {
                return true;
            }
        }

        return false;
    }

    public static function keyToValue(string $key): int
    {
        $key = mb_strtoupper($key);
        self::throwIfKeyInvalid($key);
        return self::getConstants()[$key];
    }

    public static function valueToKey(int $value): string
    {
        self::throwIfValueInvalid($value);
        return array_search($value, self::getConstants());
    }

    public static function has(int $value): bool
    {
        return in_array($value, self::getConstants(), true);
    }

    protected static function throwIfValueInvalid(int $value): void
    {
        if (!self::has($value)) {
            throw new UnexpectedValueException('Unknown enum value: ' . $value);
        }
    }
}
