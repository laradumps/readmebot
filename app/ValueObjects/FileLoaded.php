<?php

declare(strict_types = 1);

namespace App\ValueObjects;

use App\File;
use Exception;

/** Loaded disk files  */
final class FileLoaded
{
    private readonly string $filePath;

    private string $filename;

    private string $fileContent;

    public function __construct(string $filePath, string|null $newFileContent = null)
    {
        if (! File::exists($filePath)) {
            throw new Exception("File {$filePath} doesn't exist");
        }

        $this->filePath  = $filePath;

        $this->filename  = basename($filePath);

        $this->fileContent = $newFileContent ?? File::get($filePath);
    }

    public static function open(string $filePath): self
    {
        return new self($filePath);
    }

    public function updateContent(string $newFileContent): self
    {
        return new self($this->filePath, $newFileContent);
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function filePath(): string
    {
        return $this->filePath;
    }

    public function fileContent(): string
    {
        return $this->fileContent;
    }
}
