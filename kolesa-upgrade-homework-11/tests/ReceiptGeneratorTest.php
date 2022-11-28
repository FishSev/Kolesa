<?php

declare(strict_types=1);

use App\ReceiptGenerator;
use PHPUnit\Framework\TestCase;

class ReceiptGeneratorTest extends TestCase
{
    public function testMakingReceipt(): void
    {
        $break = str_repeat('-', 30);

        $expected = "Дорогой \"Какой-то чел\"!\n{$break}\n"
        . "Спасибо за заказ: \"Билет на шоу\"\n{$break}\n"
        . "С вас было списано: 3000 тг.\n{$break}\n"
        . "Ждем вас снова!\n";

        $generator = new ReceiptGenerator();

        $actual = $generator->make(
            'Какой-то чел',
            'Билет на шоу',
            3000,
        );

        $this->assertEquals($expected, $actual);
    }
}