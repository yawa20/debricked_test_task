<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked;

interface DebrickedProcessorInterface
{
    public function uploadToDebricked(SourceInterface $source, string $sourceDir): void;

    public function queue(SourceInterface $source): void;

    public function requestStatus(SourceInterface $source): void;
}