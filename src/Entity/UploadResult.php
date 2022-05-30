<?php

namespace App\Entity;

use App\Repository\UploadResultRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: UploadResultRepository::class)]
class UploadResult implements \JsonSerializable
{
    const STATUS__UNDEFINED = 0;
    const STATUS__COMPLETED = 200;
    const STATUS__IN_PROGRESS = 202;
    const STATUS__LONG_TIME = 201;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(inversedBy: 'uploadResult', targetEntity: UploadEntity::class)]
    #[ORM\JoinColumn(name: 'ci_upload_id', referencedColumnName: 'ci_upload_id' )]
    private UploadEntity $uploadEntity;

    #[ORM\Column(type: 'integer')]
    private int $status = self::STATUS__UNDEFINED;

    #[ORM\Column(type: 'integer')]
    private int $progress = 0;

    #[ORM\Column(type: 'integer')]
    private int $vulnerabilitiesFound = 0;

    #[ORM\Column(type: 'integer')]
    private int $unaffectedVulnerabilitiesFound = 0;

    public function __construct(UploadEntity $uploadEntity)
    {
        $this->uploadEntity = $uploadEntity;
    }

    public function getUploadEntity(): UploadEntity
    {
        return $this->uploadEntity;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status):void
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
        'unaffectedVulnerabilitiesFound' => "int"
    ])] public function jsonSerialize(): array
    {
        return [
            'ciUploadId' => $this->uploadEntity->getId(),
            'status' => $this->status,
            'progress' => $this->progress,
            'vulnerabilitiesFound' => $this->vulnerabilitiesFound,
            'unaffectedVulnerabilitiesFound' => $this->unaffectedVulnerabilitiesFound
        ];
    }
}
