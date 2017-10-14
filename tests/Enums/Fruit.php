<?php

namespace TypedEnum\Tests\Enums;

final class Fruit extends \TypedEnum\IntegerEnum
{
    const BANANA = 1;
    const CHERRY = 2;
    const ORANGE = 3;

    protected static $constants = [
        'BANANA' => self::BANANA,
        'CHERRY' => self::CHERRY,
        'ORANGE' => self::ORANGE
    ];
}
