<?php
declare(strict_types=1);

namespace TypedEnum\Tests;

use TypedEnum\Traits\StringUtils;

final class StringUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function testStartsWith()
    {
        $stringUtils = new class { use StringUtils; };

        $this->assertTrue($stringUtils->startsWith('isAaAa', 'is'));
        $this->assertFalse($stringUtils->startsWith('noAaAa', 'is'));
    }

    public function testStringAfter()
    {
        $stringUtils = new class { use StringUtils; };

        $this->assertSame('AaAa', $stringUtils->stringAfter('isAaAa', 'is'));
        $this->assertSame('noAaAa', $stringUtils->stringAfter('noAaAa', 'is'));
    }

    public function testToConstantsCase()
    {
        $stringUtils = new class { use StringUtils; };

        $this->assertSame('AA', $stringUtils->toConstantsCase('Aa'));
        $this->assertSame('AA_AA', $stringUtils->toConstantsCase('AaAa'));
        $this->assertSame('AA_AA_AA', $stringUtils->toConstantsCase('AaAaAa'));
    }
}
