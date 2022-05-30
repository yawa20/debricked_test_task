<?php

declare(strict_types=1);

namespace App\DebrickedApi;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface DebrickedApiInterface
{
    public function uploadFile(
        string $filepath,
        string $repositoryName,
        string $commitName,
    ): ResponseInterface;

    public function queueUploads(
        int $ciUploadId,
        string $repositoryName,
        string $commitName
    ): ResponseInterface;

    public function getStatus(int $ciUploadId): ResponseInterface;
}