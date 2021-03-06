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
     * @param string|null $expected
     * @param string|null $original
     */
    public function testPhoneNumbersPreparation(?string $expected, ?string $original)
    {
        $actual = StringHelper::preparePhone($original);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider emailsProvider
     *
     * @param string|null $expected
     * @param string|null $original
     */
    public function tesEmailsPreparation(?string $expected, ?string $original)
    {
        $actual = StringHelper::preparePhone($original);

        $this->assertEquals($expected, $actual);
    }

    public function phoneNumbersProvider(): Generator
    {
        yield ['', null];
        yield ['', ''];
        yield ['', ' '];

        yield ['+375292186303', ' +375 (29) 218-63-03 '];
        yield ['+375291918253', '+375 (29) 1918 25 3'];
        yield ['+375331234567', '+375 (33) 1234567'];
        yield ['+375441234567', '+375 44 1234567'];
        yield ['375257654321', '375 25 765 43 21'];
        yield ['257654321', '25 765 43 21'];
        yield ['+375251234567', '+375251234567'];
        yield ['', '37525123'];
        yield ['', '+37525123'];
        yield ['+79039030303', '+7 (903) 903-03-03'];
        yield ['', '+7 (903) 903-0'];
    }

    public function emailsProvider(): Generator
    {
        yield ['', null];
        yield ['', ''];
        yield ['', ' '];

        yield ['Kastus@Kalinouski.by', ' Kastus@Kalinouski.by'];
        yield ['', 'Kastus @Kalinouski.by'];
        yield ['', '.by'];

        yield ['Taras@Shevchenko.ua', 'Taras@Shevchenko.ua '];
        yield ['', 'Taras Shevchenko'];
        yield [null, 'Taras@Shevchenko'];

        yield ['Mikhail@Glinka.ru', ' Mikhail@Glinka.ru '];
        yield ['', 'Mikhail'];
        yield ['', '@Glinka.ru'];
    }
}
