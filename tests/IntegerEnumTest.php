<?php
declare(strict_types=1);

namespace TypedEnum\Tests;

use BadMethodCallException;
use UnexpectedValueException;

use TypedEnum\Tests\Enums\Color;
use TypedEnum\Tests\Enums\Fruit;

final class IntegerEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testBadDynamicMethodCall()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Unknown method: badDynamicMethod');

        Color::makeYellow()->badDynamicMethod();
    }

    public function testBadStaticMethodCall()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Unknown method: badStaticMethod');

        Color::badStaticMethod();
    }

    public function testEnumValues()
    {
        $this->assertSame(1, Color::YELLOW);
        $this->assertNotSame(2, Color::YELLOW);
        $this->assertNotSame(3, Color::YELLOW);

        $this->assertNotSame(1, Color::PURPLE);
        $this->assertSame(2, Color::PURPLE);
        $this->assertNotSame(3, Color::PURPLE);

        $this->assertNotSame(1, Color::ORANGE);
        $this->assertNotSame(2, Color::ORANGE);
        $this->assertSame(3, Color::ORANGE);
    }

    public function testGetConstants()
    {
        $expectedConstants = [
            'YELLOW' => 1,
            'PURPLE' => 2,
            'ORANGE' => 3
        ];

        $this->assertSame($expectedConstants, Color::getConstants());
    }

    public function testConstructor()
    {
        $color = new Color(Color::PURPLE);

        $this->assertInstanceOf(Color::class, $color);
        $this->assertSame(Color::PURPLE, $color->getValue());
        $this->assertSame('PURPLE', $color->getKey());
    }

    public function testConstructorException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown enum value: 4');

        new Color(4);
    }

    public function testMake()
    {
        $color = Color::make(Color::PURPLE);

        $this->assertInstanceOf(Color::class, $color);
        $this->assertSame(Color::PURPLE, $color->getValue());
        $this->assertSame('PURPLE', $color->getKey());
    }

    public function testMakeException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown enum value: 4');

        Color::make(4);
    }

    public function testWithKey()
    {
        $color = Color::withKey('purple');

        $this->assertInstanceOf(Color::class, $color);
        $this->assertSame(2, $color->getValue());
        $this->assertSame('PURPLE', $color->getKey());
    }

    public function testWithKeyException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown enum key: SILVER');

        Color::withKey('silver');
    }

    public function testMagicMake()
    {
        $color = Color::makePurple();

        $this->assertInstanceOf(Color::class, $color);
        $this->assertSame(2, $color->getValue());
        $this->assertSame('PURPLE', $color->getKey());
    }

    public function testMagicMakeException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown enum key: SILVER');

        Color::makeSilver();
    }

    public function testParseKey()
    {
        $color = Color::parse('purple');

        $this->assertInstanceOf(Color::class, $color);
        $this->assertSame(2, $color->getValue());
        $this->assertSame('PURPLE', $color->getKey());
    }

    public function testParseKeyException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Argument could not be parsed to enum: silver');

        Color::parse('silver');
    }

    public function testParseIntegerValue()
    {
        $color = Color::parse(2);

        $this->assertInstanceOf(Color::class, $color);
        $this->assertSame(2, $color->getValue());
        $this->assertSame('PURPLE', $color->getKey());
    }

    public function testParseIntegerValueException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Argument could not be parsed to enum: 4');

        Color::parse(4);
    }

    public function testParseStringValue()
    {
        $color = Color::parse('2');

        $this->assertInstanceOf(Color::class, $color);
        $this->assertSame(2, $color->getValue());
        $this->assertSame('PURPLE', $color->getKey());
    }

    public function testParseStringValueException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Argument could not be parsed to enum: 4');

        Color::parse('4');
    }

    public function testParseEquality()
    {
        $this->assertEquals(Color::parse('2'), Color::parse('purple'));
    }

    public function testIs()
    {
        $this->assertTrue(Color::make(Color::YELLOW)->is(Color::YELLOW));
        $this->assertFalse(Color::make(Color::YELLOW)->is(Color::PURPLE));
        $this->assertFalse(Color::make(Color::YELLOW)->is(Color::ORANGE));

        $this->assertFalse(Color::make(Color::PURPLE)->is(Color::YELLOW));
        $this->assertTrue(Color::make(Color::PURPLE)->is(Color::PURPLE));
        $this->assertFalse(Color::make(Color::PURPLE)->is(Color::ORANGE));

        $this->assertFalse(Color::make(Color::ORANGE)->is(Color::YELLOW));
        $this->assertFalse(Color::make(Color::ORANGE)->is(Color::PURPLE));
        $this->assertTrue(Color::make(Color::ORANGE)->is(Color::ORANGE));
    }

    public function testVariadicIs()
    {
        $color = Color::make(Color::YELLOW);

        $this->assertTrue($color->is(Color::ORANGE, Color::PURPLE, Color::YELLOW));
        $this->assertTrue($color->is(Color::PURPLE, Color::YELLOW));
        $this->assertFalse($color->is(Color::ORANGE, Color::PURPLE));
    }

    public function testMagicIs()
    {
        $this->assertTrue(Color::makeYellow()->isYellow());
        $this->assertFalse(Color::makeYellow()->isPurple());
        $this->assertFalse(Color::makeYellow()->isOrange());

        $this->assertFalse(Color::makePurple()->isYellow());
        $this->assertTrue(Color::makePurple()->isPurple());
        $this->assertFalse(Color::makePurple()->isOrange());

        $this->assertFalse(Color::makeOrange()->isYellow());
        $this->assertFalse(Color::makeOrange()->isPurple());
        $this->assertTrue(Color::makeOrange()->isOrange());
    }

    public function testSameAs()
    {
        $this->assertTrue(Color::makeYellow()->sameAs(Color::makeYellow()));
        $this->assertFalse(Color::makeYellow()->sameAs(Color::makePurple()));
        $this->assertFalse(Color::makeYellow()->sameAs(Color::makeOrange()));

        $this->assertFalse(Color::makePurple()->sameAs(Color::makeYellow()));
        $this->assertTrue(Color::makePurple()->sameAs(Color::makePurple()));
        $this->assertFalse(Color::makePurple()->sameAs(Color::makeOrange()));

        $this->assertFalse(Color::makeOrange()->sameAs(Color::makeYellow()));
        $this->assertFalse(Color::makeOrange()->sameAs(Color::makePurple()));
        $this->assertTrue(Color::makeOrange()->sameAs(Color::makeOrange()));
    }

    public function testVariadicSameAs()
    {
        $color = Color::make(Color::YELLOW);

        $orange = Color::makeOrange();
        $purple = Color::makePurple();
        $yellow = Color::makeYellow();

        $this->assertTrue($color->sameAs($orange, $purple, $yellow));
        $this->assertTrue($color->sameAs($purple, $yellow));
        $this->assertFalse($color->sameAs($orange, $purple));
    }

    public function testSameAsFailSafety()
    {
        $this->assertTrue(Color::ORANGE === Fruit::ORANGE);
        $this->assertFalse(Color::makeOrange()->sameAs(Fruit::makeOrange()));
    }

    public function testStaticGetValue()
    {
        $this->assertSame(1, Color::getValue('yellow'));
        $this->assertNotSame(1, Color::getValue('purple'));
        $this->assertNotSame(1, Color::getValue('orange'));

        $this->assertNotSame(2, Color::getValue('yellow'));
        $this->assertSame(2, Color::getValue('purple'));
        $this->assertNotSame(2, Color::getValue('orange'));

        $this->assertNotSame(3, Color::getValue('yellow'));
        $this->assertNotSame(3, Color::getValue('purple'));
        $this->assertSame(3, Color::getValue('orange'));
    }

    public function testStaticGetKey()
    {
        $this->assertSame('YELLOW', Color::getKey(Color::YELLOW));
        $this->assertNotSame('YELLOW', Color::getKey(Color::PURPLE));
        $this->assertNotSame('YELLOW', Color::getKey(Color::ORANGE));

        $this->assertNotSame('PURPLE', Color::getKey(Color::YELLOW));
        $this->assertSame('PURPLE', Color::getKey(Color::PURPLE));
        $this->assertNotSame('PURPLE', Color::getKey(Color::ORANGE));

        $this->assertNotSame('ORANGE', Color::getKey(Color::YELLOW));
        $this->assertNotSame('ORANGE', Color::getKey(Color::PURPLE));
        $this->assertSame('ORANGE', Color::getKey(Color::ORANGE));
    }

    public function testDynamicGetValue()
    {
        $this->assertSame(1, Color::makeYellow()->getValue());
        $this->assertNotSame(1, Color::makePurple()->getValue());
        $this->assertNotSame(1, Color::makeOrange()->getValue());

        $this->assertNotSame(2, Color::makeYellow()->getValue());
        $this->assertSame(2, Color::makePurple()->getValue());
        $this->assertNotSame(2, Color::makeOrange()->getValue());

        $this->assertNotSame(3, Color::makeYellow()->getValue());
        $this->assertNotSame(3, Color::makePurple()->getValue());
        $this->assertSame(3, Color::makeOrange()->getValue());
    }

    public function testDynamicGetKey()
    {
        $this->assertSame('YELLOW', Color::makeYellow()->getKey());
        $this->assertNotSame('YELLOW', Color::makePurple()->getKey());
        $this->assertNotSame('YELLOW', Color::makeOrange()->getKey());

        $this->assertNotSame('PURPLE', Color::makeYellow()->getKey());
        $this->assertSame('PURPLE', Color::makePurple()->getKey());
        $this->assertNotSame('PURPLE', Color::makeOrange()->getKey());

        $this->assertNotSame('ORANGE', Color::makeYellow()->getKey());
        $this->assertNotSame('ORANGE', Color::makePurple()->getKey());
        $this->assertSame('ORANGE', Color::makeOrange()->getKey());
    }

    public function testStringSerialization()
    {
        $this->assertSame('2', (string) Color::makePurple());
    }

    public function testJsonSerialization()
    {
        $data = json_decode(json_encode([
            'color' => Color::makePurple()
        ]));

        $this->assertSame(2, $data->color);
    }
}
