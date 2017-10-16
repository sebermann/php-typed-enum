<?php

namespace TypedEnum\Tests\Enums;

final class Fruit extends \TypedEnum\IntegerEnum
{
    public const BANANA = 1;
    public const CHERRY = 2;
    public const ORANGE = 3;

    protected static $constants = [
        'BANANA' => self::BANANA,
        'CHERRY' => self::CHERRY,
        'ORANGE' => self::ORANGE
    ];
}
