<?php

namespace TypedEnum\Tests\Enums;

final class Language extends \TypedEnum\StringEnum
{
    const HEBREW = 'heb';
    const FRENCH = 'fre';
    const POLISH = 'pol';

    protected static $constants = [
        'HEBREW' => self::HEBREW,
        'FRENCH' => self::FRENCH,
        'POLISH' => self::POLISH
    ];
}
