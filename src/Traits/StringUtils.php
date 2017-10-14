<?php

namespace TypedEnum\Traits;

trait StringUtils
{
    public static function startsWith(string $haystack, string $needle): bool
    {
        return mb_substr($haystack, 0, mb_strlen($needle)) === $needle;
    }

    public static function stringAfter(string $subject, string $search): string
    {
        return array_reverse(explode($search, $subject, 2))[0];
    }

    public static function toConstantsCase(string $string): string
    {
        return mb_strtoupper(
            preg_replace(
                ['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'],
                '$1_$2',
                $string
            )
        );
    }
}
