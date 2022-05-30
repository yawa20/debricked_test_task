<?php

declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ClientFileUploader
{
    public function __construct(
        private string $uploadDir,
        private SluggerInterface $slugger,
    ) {
    }

    /**
     * store file in filesystem and return it`s path
     */
    public function upload(UploadedFile $file, string $prefix): string
    {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $prefix.'-'.$safeFilename.'.'.$file->guessExtension();
        $file->move($this->uploadDir, $fileName);

        return $fileName;
    }
}