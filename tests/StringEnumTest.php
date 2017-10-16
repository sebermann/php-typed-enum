<?php
declare(strict_types=1);

namespace TypedEnum\Tests;

use BadMethodCallException;
use UnexpectedValueException;

use TypedEnum\Tests\Enums\Country;
use TypedEnum\Tests\Enums\Language;

final class StringEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testBadDynamicMethodCall()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Unknown method: badDynamicMethod');

        Country::makeIsrael()->badDynamicMethod();
    }

    public function testBadStaticMethodCall()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Unknown method: badStaticMethod');

        Country::badStaticMethod();
    }

    public function testEnumValues()
    {
        $this->assertSame('isr', Country::ISRAEL);
        $this->assertNotSame('fra', Country::ISRAEL);
        $this->assertNotSame('pol', Country::ISRAEL);

        $this->assertNotSame('isr', Country::FRANCE);
        $this->assertSame('fra', Country::FRANCE);
        $this->assertNotSame('pol', Country::FRANCE);

        $this->assertNotSame('isr', Country::POLAND);
        $this->assertNotSame('fra', Country::POLAND);
        $this->assertSame('pol', Country::POLAND);
    }

    public function testGetConstants()
    {
        $expectedConstants = [
            'ISRAEL' => 'isr',
            'FRANCE' => 'fra',
            'POLAND' => 'pol'
        ];

        $this->assertSame($expectedConstants, Country::getConstants());
    }

    public function testGetKeys()
    {
        $expectedKeys = [
            'ISRAEL',
            'FRANCE',
            'POLAND'
        ];

        $this->assertSame($expectedKeys, Country::getKeys());
    }

    public function testGetValues()
    {
        $expectedValues = [
            'isr',
            'fra',
            'pol'
        ];

        $this->assertSame($expectedValues, Country::getValues());
    }

    public function testHasKey()
    {
        $this->assertTrue(Country::hasKey('ISRAEL'));
        $this->assertTrue(Country::hasKey('FRANCE'));
        $this->assertTrue(Country::hasKey('POLAND'));
        $this->assertFalse(Country::hasKey('CANADA'));
    }

    public function testHasValue()
    {
        $this->assertTrue(Country::hasValue('isr'));
        $this->assertTrue(Country::hasValue('fra'));
        $this->assertTrue(Country::hasValue('pol'));
        $this->assertFalse(Country::hasValue('can'));
    }

    public function testConstructor()
    {
        $country = new Country(Country::FRANCE);

        $this->assertInstanceOf(Country::class, $country);
        $this->assertSame(Country::FRANCE, $country->getValue());
        $this->assertSame('FRANCE', $country->getKey());
    }

    public function testConstructorException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown enum value: can');

        new Country('can');
    }

    public function testMake()
    {
        $country = Country::make(Country::FRANCE);

        $this->assertInstanceOf(Country::class, $country);
        $this->assertSame(Country::FRANCE, $country->getValue());
        $this->assertSame('FRANCE', $country->getKey());
    }

    public function testMakeException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown enum value: can');

        Country::make('can');
    }

    public function testWithKey()
    {
        $country = Country::withKey('france');

        $this->assertInstanceOf(Country::class, $country);
        $this->assertSame('fra', $country->getValue());
        $this->assertSame('FRANCE', $country->getKey());
    }

    public function testWithKeyException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown enum key: CANADA');

        Country::withKey('canada');
    }

    public function testMagicMake()
    {
        $country = Country::makeFrance();

        $this->assertInstanceOf(Country::class, $country);
        $this->assertSame('fra', $country->getValue());
        $this->assertSame('FRANCE', $country->getKey());
    }

    public function testMagicMakeException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown enum key: CANADA');

        Country::makeCanada();
    }

    public function testParseKey()
    {
        $country = Country::parse('france');

        $this->assertInstanceOf(Country::class, $country);
        $this->assertSame('fra', $country->getValue());
        $this->assertSame('FRANCE', $country->getKey());
    }

    public function testParseKeyException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Argument could not be parsed to enum: canada');

        Country::parse('canada');
    }

    public function testParseValue()
    {
        $country = Country::parse('fra');

        $this->assertInstanceOf(Country::class, $country);
        $this->assertSame('fra', $country->getValue());
        $this->assertSame('FRANCE', $country->getKey());
    }

    public function testParseValueException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Argument could not be parsed to enum: can');

        Country::parse('can');
    }

    public function testParseEquality()
    {
        $this->assertEquals(Country::parse('fra'), Country::parse('france'));
    }

    public function testIs()
    {
        $this->assertTrue(Country::make(Country::ISRAEL)->is(Country::ISRAEL));
        $this->assertFalse(Country::make(Country::ISRAEL)->is(Country::FRANCE));
        $this->assertFalse(Country::make(Country::ISRAEL)->is(Country::POLAND));

        $this->assertFalse(Country::make(Country::FRANCE)->is(Country::ISRAEL));
        $this->assertTrue(Country::make(Country::FRANCE)->is(Country::FRANCE));
        $this->assertFalse(Country::make(Country::FRANCE)->is(Country::POLAND));

        $this->assertFalse(Country::make(Country::POLAND)->is(Country::ISRAEL));
        $this->assertFalse(Country::make(Country::POLAND)->is(Country::FRANCE));
        $this->assertTrue(Country::make(Country::POLAND)->is(Country::POLAND));
    }

    public function testVariadicIs()
    {
        $country = Country::make(Country::ISRAEL);

        $this->assertTrue($country->is(Country::POLAND, Country::FRANCE, Country::ISRAEL));
        $this->assertTrue($country->is(Country::FRANCE, Country::ISRAEL));
        $this->assertFalse($country->is(Country::POLAND, Country::FRANCE));
    }

    public function testMagicIs()
    {
        $this->assertTrue(Country::makeIsrael()->isIsrael());
        $this->assertFalse(Country::makeIsrael()->isFrance());
        $this->assertFalse(Country::makeIsrael()->isPoland());

        $this->assertFalse(Country::makeFrance()->isIsrael());
        $this->assertTrue(Country::makeFrance()->isFrance());
        $this->assertFalse(Country::makeFrance()->isPoland());

        $this->assertFalse(Country::makePoland()->isIsrael());
        $this->assertFalse(Country::makePoland()->isFrance());
        $this->assertTrue(Country::makePoland()->isPoland());
    }

    public function testSameAs()
    {
        $this->assertTrue(Country::makeIsrael()->sameAs(Country::makeIsrael()));
        $this->assertFalse(Country::makeIsrael()->sameAs(Country::makeFrance()));
        $this->assertFalse(Country::makeIsrael()->sameAs(Country::makePoland()));

        $this->assertFalse(Country::makeFrance()->sameAs(Country::makeIsrael()));
        $this->assertTrue(Country::makeFrance()->sameAs(Country::makeFrance()));
        $this->assertFalse(Country::makeFrance()->sameAs(Country::makePoland()));

        $this->assertFalse(Country::makePoland()->sameAs(Country::makeIsrael()));
        $this->assertFalse(Country::makePoland()->sameAs(Country::makeFrance()));
        $this->assertTrue(Country::makePoland()->sameAs(Country::makePoland()));
    }

    public function testVariadicSameAs()
    {
        $country = Country::make(Country::ISRAEL);

        $poland = Country::makePoland();
        $france = Country::makeFrance();
        $israel = Country::makeIsrael();

        $this->assertTrue($country->sameAs($poland, $france, $israel));
        $this->assertTrue($country->sameAs($france, $israel));
        $this->assertFalse($country->sameAs($poland, $france));
    }

    public function testSameAsFailSafety()
    {
        $this->assertTrue(Country::POLAND === Language::POLISH);
        $this->assertFalse(Country::makePoland()->sameAs(Language::makePolish()));
    }

    public function testKeyToValue()
    {
        $this->assertSame('isr', Country::keyToValue('israel'));
        $this->assertNotSame('isr', Country::keyToValue('france'));
        $this->assertNotSame('isr', Country::keyToValue('poland'));

        $this->assertNotSame('fra', Country::keyToValue('israel'));
        $this->assertSame('fra', Country::keyToValue('france'));
        $this->assertNotSame('fra', Country::keyToValue('poland'));

        $this->assertNotSame('pol', Country::keyToValue('israel'));
        $this->assertNotSame('pol', Country::keyToValue('france'));
        $this->assertSame('pol', Country::keyToValue('poland'));
    }

    public function testValueToKey()
    {
        $this->assertSame('ISRAEL', Country::valueToKey(Country::ISRAEL));
        $this->assertNotSame('ISRAEL', Country::valueToKey(Country::FRANCE));
        $this->assertNotSame('ISRAEL', Country::valueToKey(Country::POLAND));

        $this->assertNotSame('FRANCE', Country::valueToKey(Country::ISRAEL));
        $this->assertSame('FRANCE', Country::valueToKey(Country::FRANCE));
        $this->assertNotSame('FRANCE', Country::valueToKey(Country::POLAND));

        $this->assertNotSame('POLAND', Country::valueToKey(Country::ISRAEL));
        $this->assertNotSame('POLAND', Country::valueToKey(Country::FRANCE));
        $this->assertSame('POLAND', Country::valueToKey(Country::POLAND));
    }

    public function testGetValue()
    {
        $this->assertSame('isr', Country::makeIsrael()->getValue());
        $this->assertNotSame('isr', Country::makeFrance()->getValue());
        $this->assertNotSame('isr', Country::makePoland()->getValue());

        $this->assertNotSame('fra', Country::makeIsrael()->getValue());
        $this->assertSame('fra', Country::makeFrance()->getValue());
        $this->assertNotSame('fra', Country::makePoland()->getValue());

        $this->assertNotSame('pol', Country::makeIsrael()->getValue());
        $this->assertNotSame('pol', Country::makeFrance()->getValue());
        $this->assertSame('pol', Country::makePoland()->getValue());
    }

    public function testGetKey()
    {
        $this->assertSame('ISRAEL', Country::makeIsrael()->getKey());
        $this->assertNotSame('ISRAEL', Country::makeFrance()->getKey());
        $this->assertNotSame('ISRAEL', Country::makePoland()->getKey());

        $this->assertNotSame('FRANCE', Country::makeIsrael()->getKey());
        $this->assertSame('FRANCE', Country::makeFrance()->getKey());
        $this->assertNotSame('FRANCE', Country::makePoland()->getKey());

        $this->assertNotSame('POLAND', Country::makeIsrael()->getKey());
        $this->assertNotSame('POLAND', Country::makeFrance()->getKey());
        $this->assertSame('POLAND', Country::makePoland()->getKey());
    }

    public function testStringSerialization()
    {
        $this->assertSame('fra', (string) Country::makeFrance());
    }

    public function testJsonSerialization()
    {
        $data = json_decode(json_encode([
            'country' => Country::makeFrance()
        ]));

        $this->assertSame('fra', $data->country);
    }
}
