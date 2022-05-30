<?php

declare(strict_types=1);

namespace App\Debricked;

interface SourceInterface
{
    public const STATUS__NEW = 'new';
    public const STATUS__FAILED = 'failed';
    public const STATUS__UPLOADED = 'uploaded';
    public const STATUS__COMMITED = 'comited';
    public const STATUS__PROCESSING = 'processing';
    public const STATUS__FINISHED = 'finished';


    public function getFilename(): ?string;

    public function getRepositoryName(): ?string;

    public function getCommitName(): ?string;

    public function getCiUploadId(): ?int;

    public function setCiUploadId(?int $ciUploadId): void;

    public function setStatus(string $status): void;

    public function getStatus(): ?string;

    public function markUploaded(): void;

    public function markFailed(): void;

    public function progressTo(int $resultStatus, array $resultData): void;
}