<?php

namespace App\Entity;

use App\Repository\UploadEntityRepository;
use App\RequestDTO\UploadFilesRequestDTO;
use App\ValueObject\Trigger;
use DateTimeImmutable;
use DebrickedBundle\Debricked\SourceInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: UploadEntityRepository::class)]
class UploadEntity implements SourceInterface, JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $filename;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = SourceInterface::STATUS__NEW;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $uploadedAt;

    #[ORM\Column(type: 'string', length: 255)]
    private string $repositoryName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $commitName;

    #[ORM\Column(type: 'integer', unique: true, nullable: true)]
    private ?int $ciUploadId;

    #[ORM\Column(type: 'triggers_collection')]
    private array $triggers = [];

    public static function fromRequestUpload(
        string $filename,
        UploadFilesRequestDTO $DTO
    ): self {
        $self = new self();
        $self->createdAt = new DateTimeImmutable('now');
        $self->commitName = $DTO->commitName;
        $self->repositoryName = $DTO->repositoryName;
        $self->filename = $filename;
        $self->triggers = $DTO->triggers;

        return $self;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @internal - required for doctrine, newer use this in code
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @internal - required for doctrine, newer use this in code
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @internal - required for doctrine, newer use this in code
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUploadedAt(): ?DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    /**
     * @internal - required for doctrine, newer use this in code
     */
    public function setUploadedAt(?DateTimeImmutable $uploadedAt): void
    {
        $this->uploadedAt = $uploadedAt;
    }

    public function getRepositoryName(): ?string
    {
        return $this->repositoryName;
    }

    /**
     * @internal - required for doctrine, newer use this in code
     */
    public function setRepositoryName(string $repositoryName): void
    {
        $this->repositoryName = $repositoryName;
    }

    public function getCommitName(): ?string
    {
        return $this->commitName;
    }

    /**
     * @internal - required for doctrine, newer use this in code
     */
    public function setCommitName(string $commitName): void
    {
        $this->commitName = $commitName;
    }

    /**
     * @return int|null
     */
    public function getCiUploadId(): ?int
    {
        return $this->ciUploadId;
    }

    /**
     * @internal - required for doctrine, newer use this in code
     */
    public function setCiUploadId(?int $ciUploadId): void
    {
        $this->ciUploadId = $ciUploadId;
    }

    /**
     * @return UploadResult
     */
    public function getUploadResult(): UploadResult
    {
        return $this->uploadResult;
    }

    /**
     * @return Trigger[]
     */
    public function getTriggers(): array
    {
        return $this->triggers;
    }

    /**
     * @param array $triggers
     */
    public function setTriggers(array $triggers): void
    {
        $this->triggers = $triggers;
    }

    /**
     * @internal - required for doctrine, newer use this in code
     */
    public function setUploadResult(UploadResult $uploadResult): void
    {
        $this->uploadResult = $uploadResult;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'status' => $this->status,
            'createdAt' => $this->createdAt->format("Y-m-d H:i:s"),
            'uploadedAt' => $this->uploadedAt?->format("Y-m-d H:i:s"),
            'repositoryName' => $this->repositoryName,
            'commitName' => $this->commitName,
            'trigers' => $this->triggers,
        ];
    }

    public function markUploaded(): void
    {
        $this->status = self::STATUS__UPLOADED;
        $this->setUploadedAt(new DateTimeImmutable("now"));
    }

    public function markFailed(): void
    {
        $this->status = self::STATUS__FAILED;
    }

    public function progressTo(int $resultStatus): void
    {
        $this->status = (200 === $resultStatus) ? self::STATUS__FINISHED : self::STATUS__PROCESSING;
    }

    public function isFinished(): bool
    {
        return self::STATUS__FINISHED === $this->status;
    }
}
