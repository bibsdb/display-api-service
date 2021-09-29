<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @Vich\Uploadable
 * @ORM\EntityListeners({"App\EventListener\MediaPrePersistEventListener"})
 */
class Media
{
    use EntityIdTrait;
    use TimestampableEntity;
    use EntityTitleDescriptionTrait;
    use EntityModificationTrait;

    public ?string $url = null;

    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="filePath")
     * @Assert\File(
     *     maxSize = "6144k",
     *     mimeTypes = {"image/jpeg", "image/png", "video/mp4"},
     *     mimeTypesMessage = "Please upload a valid jpeg, png or mp4 file"
     * )
     */
    public ?File $file = null;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $filePath = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": ""})
     */
    private string $license = '';

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private int $width = 0;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private int $height = 0;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private int $size = 0;

    /**
     * @ORM\Column(type="string", options={"default": ""})
     */
    private string $mimeType = '';

    /**
     * @ORM\Column(type="string", options={"default": ""})
     */
    private string $sha = '';

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSha(): string
    {
        return $this->sha;
    }

    public function setSha(string $sha): self
    {
        $this->sha = $sha;

        return $this;
    }

    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function getLicense(): string
    {
        return $this->license;
    }

    public function setLicense(string $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }
}
