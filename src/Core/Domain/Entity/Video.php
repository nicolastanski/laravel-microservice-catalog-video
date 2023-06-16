<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Enum\Rating;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Video
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalf = null,
        protected ?Image $bannerFile = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
        protected ?Uuid $id = null,
        protected bool $published = false,
        protected ?DateTime $createdAt = null
    ) {
        $this->id = $this->id ?? Uuid::random();

        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validation();
    }

    public function addCategoryId(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategoryId(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    public function addGenre(string $genreId)
    {
        array_push($this->genresId, $genreId);
    }

    public function removeGenre(string $genreId)
    {
        unset($this->genresId[array_search($genreId, $this->genresId)]);
    }

    public function addCastMember(string $castMemberId)
    {
        array_push($this->castMembersId, $castMemberId);
    }

    public function removeCastMember(string $castMemberId)
    {
        unset($this->castMembersId[array_search($castMemberId, $this->castMembersId)]);
    }

    public function thumbFile(): ?Image
    {
        return $this->thumbfile;
    }

    public function thumbHalf(): ?Image
    {
        return $this->thumbhalf;
    }

    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }

    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }

    private function validation()
    {
        DomainValidation::notNull($this->title);
        DomainValidation::strMinLength($this->title);
        DomainValidation::strCanNullAndMaxLength($this->description);
    }
}
