<?php

namespace TypedEnum\Tests\Enums;

final class Language extends \TypedEnum\StringEnum
{
    public const HEBREW = 'heb';
    public const FRENCH = 'fre';
    public const POLISH = 'pol';

    protected static $constants = [
        'HEBREW' => self::HEBREW,
        'FRENCH' => self::FRENCH,
        'POLISH' => self::POLISH
    ];
}
