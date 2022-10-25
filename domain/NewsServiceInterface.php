<?php declare(strict_types=1);

namespace app\domain;

interface NewsServiceInterface
{
    public function saveNews(): void;
}
