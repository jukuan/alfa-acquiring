<?php

declare(strict_types=1);

namespace Tests\Helper;

use AlfaAcquiring\Helper\StringHelper;
use Generator;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    /**
     * @dataProvider phoneNumbersProvider
     *
     * @param $expected
     * @param $original
     */
    public function testPhoneNumbersPreparation($expected, $original)
    {
        $actual = StringHelper::preparePhone($original);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider emailsProvider
     *
     * @param $expected
     * @param $original
     */
    public function tesEmailsPreparation($expected, $original)
    {
        $actual = StringHelper::preparePhone($original);

        $this->assertEquals($expected, $actual);
    }

    public function phoneNumbersProvider(): Generator
    {
        yield ['+375292186303', ' +375 (29) 218-63-03 '];
        yield ['+375291918253', '+375 (29) 1918 25 3'];
        yield ['+375331234567', '+375 (33) 1234567'];
        yield ['+375441234567', '+375 44 1234567'];
        yield ['375257654321', '375 25 765 43 21'];
        yield ['257654321', '25 765 43 21'];
        yield ['+375251234567', '+375251234567'];
        yield [null, '37525123'];
        yield [null, '+37525123'];
        yield ['+79039030303', '+7 (903) 903-03-03'];
        yield [null, '+7 (903) 903-0'];
    }

    public function emailsProvider(): Generator
    {
        yield ['Kastus@Kalinouski.by', ' Kastus@Kalinouski.by'];
        yield [null, 'Kastus @Kalinouski.by'];
        yield [null, '.by'];

        yield ['Taras@Shevchenko.ua', 'Taras@Shevchenko.ua '];
        yield [null, 'Taras Shevchenko'];
        yield [null, 'Taras@Shevchenko'];

        yield ['Mikhail@Glinka.ru', ' Mikhail@Glinka.ru '];
        yield [null, 'Mikhail'];
        yield [null, '@Glinka.ru'];
    }
}
