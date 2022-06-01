<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked;

class UploadStatusDTO
{
    public function __construct(
        public readonly int $ciUploadId,
        public readonly int $status,
        public readonly int $vulnerabilitiesFound,
        public readonly int $unaffectedVulnerabilitiesFound,
        public readonly int $progress,
    )
    {
    }

    public static function fromJson(string $json, int $status): self
    {
        $data = json_decode($json);
        return new self(
            ciUploadId: $data->ciUploadId,
            status: $status,
            vulnerabilitiesFound:  $data->vulnerabilitiesFound,
            unaffectedVulnerabilitiesFound:  $data->unaffectedVulnerabilitiesFound,
            progress:  $data->progress,
        );
    }
}