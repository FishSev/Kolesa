<?php

declare(strict_types=1);

namespace App;

class ReceiptsRepository
{
    // директория, куда сохраняем чеки
    protected string $dir;

    public function __construct(string $dir)
    {
        $this->dir = $dir;
    }

    public function save(string $filename, string $receipt): void
    {
        file_put_contents("{$this->dir}/{$filename}", $receipt);
    }
}