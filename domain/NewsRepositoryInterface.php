<?php declare(strict_types=1);

namespace app\domain;

use app\domain\News as DomainNews;

interface NewsRepositoryInterface
{
    public function create(DomainNews $newsDomain): void;
    public function existByOrderId(string $ownerId): bool;
}
