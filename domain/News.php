<?php declare(strict_types=1);

namespace app\domain;

class News
{
    private string $title;
    private string $description;
    private string $ownerId;
    private ?string $image = null;

    public function __construct(
        string $title,
        string $description,
        string $ownerId
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->ownerId = $ownerId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
}
