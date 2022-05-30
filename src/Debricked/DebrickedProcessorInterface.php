<?php

declare(strict_types=1);

namespace App\Debricked;

interface DebrickedProcessorInterface
{
    public function uploadToDebricked(SourceInterface $source, string $sourceDir): void;

    public function queue(SourceInterface $source): void;
}