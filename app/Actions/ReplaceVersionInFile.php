<?php

declare(strict_types = 1);

namespace App\Actions;

use App\File;
use App\ValueObjects\{FileLoaded, Version};
use Exception;

/** Replace versions in files with tag parsed*/
final class ReplaceVersionInFile
{
    public static function handle(Version $newVersion, FileLoaded $file): FileLoaded
    {
        $versionsToReplace      = ParseVersionTag::handle($file->fileContent());
        $originalContent        = $file->fileContent();

        $versionsToReplace->each(
            function ($actualVersion) use (&$file, $newVersion): void {
                if ($actualVersion->__toString() !== $newVersion->__toString()) {
                    $file = $file->updateContent(str_replace($actualVersion->__toString(), $newVersion->__toString(), $file->fileContent()));
                }
            }
        );
        
        if ($originalContent === $file->fileContent()) {
            throw new Exception('Nothing to update in file ' . $file->filename());
        }

        File::put($file->filePath(), $file->fileContent());

        return $file;
    }
}
