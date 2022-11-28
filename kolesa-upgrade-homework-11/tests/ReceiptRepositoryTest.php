<?php

declare(strict_types=1);

use App\ReceiptsRepository;
use PHPUnit\Framework\TestCase;

class ReceiptRepositoryTest extends TestCase
{
    protected string $tmpDir;
    protected string $filename;

    /**
     * Метод выполняется перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tmpDir = sys_get_temp_dir() . '/' . uniqid();
        mkdir($this->tmpDir);
        $this->filename = uniqid() . '.txt';
    }

    /**
     * Метод выполняется после каждого теста
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->assertTrue(unlink("$this->tmpDir/$this->filename"));
        $this->assertTrue(rmdir($this->tmpDir));
    }

    public function testReceiptWasSaved(): void
    {
        $expected = "Дорогой \"Какой-то чел\"!\n"
            . "Спасибо за заказ: \"Билет на шоу\"\n"
            . "С вас было списано: 3000 тг.\n"
            . "Ждем вас снова!\n";

        $repository = new ReceiptsRepository($this->tmpDir);

        $repository->save($this->filename, $expected);

        $actual = file_get_contents("{$this->tmpDir}/{$this->filename}");

        $this->assertEquals($expected, $actual);
    }
}