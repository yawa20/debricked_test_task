<?php

namespace App\Entity;

use App\Repository\UploadResultRepository;
use DebrickedBundle\Debricked\UploadStatusDTO;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

#[ORM\Entity(repositoryClass: UploadResultRepository::class)]
class UploadResult implements JsonSerializable
{
    const STATUS__UNDEFINED = 0;
    const STATUS__COMPLETED = 200;
    const STATUS__IN_PROGRESS = 202;
    const STATUS__LONG_TIME = 201;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'integer')]
    private int $ciUploadId;

    #[ORM\Column(type: 'integer')]
    private int $status = self::STATUS__UNDEFINED;

    #[ORM\Column(type: 'integer')]
    private int $progress = 0;

    #[ORM\Column(type: 'integer')]
    private int $vulnerabilitiesFound = 0;

    #[ORM\Column(type: 'integer')]
    private int $unaffectedVulnerabilitiesFound = 0;

    public function __construct(
        int $ciUploadId,
    ) {
        $this->ciUploadId = $ciUploadId;
    }

    public static function fromDTO(UploadStatusDTO $DTO): self
    {
        $self = new self($DTO->ciUploadId);
        $self->status = $DTO->status;
        $self->vulnerabilitiesFound = $DTO->vulnerabilitiesFound;
        $self->unaffectedVulnerabilitiesFound = $DTO->unaffectedVulnerabilitiesFound;
        $self->progress = $DTO->progress;

        return $self;
    }


    public function update(UploadStatusDTO $DTO)
    {
        $this->status = $DTO->status;
        $this->vulnerabilitiesFound = $DTO->vulnerabilitiesFound;
        $this->unaffectedVulnerabilitiesFound = $DTO->unaffectedVulnerabilitiesFound;
        $this->progress = $DTO->progress;
    }

    public function getCiUploadId(): int
    {
        return $this->ciUploadId;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(int $progress): void
    {
        $this->progress = $progress;
    }

    public function getVulnerabilitiesFound(): ?int
    {
        return $this->vulnerabilitiesFound;
    }

    public function setVulnerabilitiesFound(int $vulnerabilitiesFound): void
    {
        $this->vulnerabilitiesFound = $vulnerabilitiesFound;
    }

    public function getUnaffectedVulnerabilitiesFound(): ?int
    {
        return $this->unaffectedVulnerabilitiesFound;
    }

    public function setUnaffectedVulnerabilitiesFound(int $unaffectedVulnerabilitiesFound): void
    {
        $this->unaffectedVulnerabilitiesFound = $unaffectedVulnerabilitiesFound;
    }

    #[Pure] #[ArrayShape([
        'ciUploadId' => "int|null",
        'status' => "int",
        'progress' => "int",
        'vulnerabilitiesFound' => "int",
        'unaffectedVulnerabilitiesFound' => "int",
    ])] public function jsonSerialize(): array
    {
        return [
            'ciUploadId' => $this->ciUploadId,
            'status' => $this->status,
            'progress' => $this->progress,
            'vulnerabilitiesFound' => $this->vulnerabilitiesFound,
            'unaffectedVulnerabilitiesFound' => $this->unaffectedVulnerabilitiesFound,
        ];
    }

}
