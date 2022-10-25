<?php declare(strict_types=1);

namespace app\repository;

use app\domain\News as DomainNews;
use app\domain\NewsRepositoryInterface;
use app\models\News;

class NewsARRepository implements NewsRepositoryInterface
{
    public function create(DomainNews $newsDomain): void
    {
        $news = new News();
        $news->title = $newsDomain->getTitle();
        $news->description = $newsDomain->getDescription();
        $news->img = $newsDomain->getImage();
        $news->owner_id = $newsDomain->getOwnerId();

        $news->save();
    }

    public function existByOrderId(string $ownerId): bool
    {
        return News::find()->where(['owner_id' => $ownerId])->exists();
    }
}
