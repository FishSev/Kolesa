<?php

declare(strict_types=1);

use App\Validation\PaymentRequestValidator;
use PHPUnit\Framework\TestCase;

class PaymentRequestValidatorTest extends TestCase
{
    public function testAllGood(): void
    {
        $expected = [];

        $validator = new PaymentRequestValidator();

        $actual = $validator->validate([
            'name' => 'Севрюгин Илья',
            'cardNumber' => '123456789123',
            'expiration' => '01/22',
            'cvv' => '666',
        ]);

        $this->assertEquals($expected, $actual);
    }

    public function testAllEmpty(): void
    {
        $expected = [
            'name' => '- строка "Name on card" пустая',
            'cardNumber' => '- не введён номер карты',
            'expiration' => '- дата окончания пустая',
            'cvv' => '- CVV пустой'
        ];

        $validator = new PaymentRequestValidator();

        $actual = $validator->validate([
            'name' => '',
            'cardNumber' => '',
            'expiration' => '',
            'cvv' => '',
        ]);

        $this->assertEquals($expected, $actual);
    }

    public function testCommonErrors(): void
    {
        $expected = [
            'name' => '- длина имени и/или фамилии меньше двух символов',
            'cardNumber' => '- не введён номер карты',
            'expiration' => '- неправильный формат даты. Должен быть мм/гг',
            'cvv' => '- CVV не трёхзначное число'
        ];

        $validator = new PaymentRequestValidator();

        $actual = $validator->validate([
            'name' => 'Ice T',
            'cardNumber' => '',
            'expiration' => '1/22',
            'cvv' => 'A00',
        ]);

        $this->assertEquals($expected, $actual);

        $expected = [
            'name' => '- в строке "Name on card" не два слова (имя, фамилия) разделённые пробелом',
            'expiration' => '- месяц не в интервале [01,12] и/или год не в [22,25]',
            'cardNumber' => '- номер карты должен состоять не из 12 цифр',
            'cvv' => '- CVV не трёхзначное число'
        ];

        $validator = new PaymentRequestValidator();

        $actual = $validator->validate([
            'name' => 'ВиниПух',
            'cardNumber' => '123456789123 1',
            'expiration' => '01/29',
            'cvv' => '00',
        ]);

        $this->assertEquals($expected, $actual);
    }
}
