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

    public function is(int ...$values): bool
    {
        foreach ($values as $value) {
            if ($this->value === $value) {
                return true;
            }
        }

        return false;
    }

    protected static function keyToValue(string $key): int
    {
        self::throwIfKeyInvalid($key);
        return self::getConstants()[$key];
    }

    protected static function valueToKey(int $value): string
    {
        self::throwIfValueInvalid($value);
        return array_search($value, self::getConstants());
    }

    protected static function throwIfValueInvalid(int $value): void
    {
        if (!in_array($value, self::getConstants(), true)) {
            throw new UnexpectedValueException('Unknown enum value: ' . $value);
        }
    }
}
