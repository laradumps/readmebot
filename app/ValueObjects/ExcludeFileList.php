<?php

declare(strict_types = 1);

namespace App\ValueObjects;

use Illuminate\Support\Collection;

/** Files to exclude from scanning */
final class ExcludeFileList
{
    /**
     *
     * @var Collection<int,FileLoaded>
     */
    private Collection $excludeFileList;

    public static function fromString(string $files): self
    {
        $excludeFiles = new Collection();
        
        $files = explode(',', rtrim(ltrim(strval($files))));
        
        foreach ($files as $file) {
            if (empty($file)) {
                continue;
            }
            $excludeFiles->add(\App\ValueObjects\FileLoaded::open(ltrim(rtrim(strval($file)))));
        }

        $ExcludeFileList = new static();

        $ExcludeFileList->excludeFileList = $excludeFiles;

        return $ExcludeFileList;
    }

    /**
     * Returns all file names
     *
     * @return Collection<int, string>
     */
    public function files(): Collection
    {
        return $this->excludeFileList
            ->map(fn (FileLoaded $file) => $file->filePath());
    }
}
