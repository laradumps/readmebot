<?php

declare(strict_types = 1);

namespace App\Actions;

use App\ValueObjects\{ExcludeFileList, FileLoaded, Version};
use function Safe\preg_replace;

/** Find and update files with tag*/
final class UpdateFilesWithTag
{
    /**
     * @param excludeFileList $excludeFileList Comma separated files to be excluded from replacing
     * @param string $regexTag Regex Tag for searching files
     */
    public static function handle(Version $newVersion, ExcludeFileList $excludeFileList, string $regexTag = ''): void
    {
        if (empty($regexTag)) {
            $regexTag = '<\!--LaraDumpsVersion-->';
        }
       
        $command = 'grep -Ri "' . $regexTag . '" . | cut -d":" -f1';

        try {
            $result = RunCommand::handle($command);
        } catch (\Exception $e) {
            throw new \Exception('Searching LaraDumpsVersion tag: ' . $e->getMessage());
        }

        // Update files
        collect(explode(PHP_EOL, $result))
            ->unique()
            ->filter(fn ($file) => pathinfo($file, PATHINFO_EXTENSION) === 'md')
            ->map(fn ($file) => preg_replace('/^.\//m', '', $file))
            ->filter(fn ($file) => $excludeFileList->files()->contains($file) === false)
            ->map(fn ($file) => FileLoaded::open($file))
            ->each(fn ($file) => ReplaceVersionInFile::handle($newVersion, $file));
    }
}
