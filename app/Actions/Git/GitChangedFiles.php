<?php

declare(strict_types = 1);

namespace App\Actions\Git;

use App\Actions\RunCommand;

/** List changed files in the repository */
final class GitChangedFiles
{
    /**
     *
     *  @return array<int, string> Files
     */
    public static function handle(): array
    {
        $changedFiles = [];

        try {
            $changedFiles = RunCommand::handle('git status -s | grep -E "^ M|^\?\?" | awk \'{print $2}\'');
        } catch (\Exception $e) {
            throw new \Exception('Error searching changed files');
        }
    
        $changedFiles = explode(PHP_EOL, $changedFiles);
            
        // For the moment let's only change .md files
        return array_filter($changedFiles, fn ($file) => pathinfo($file, PATHINFO_EXTENSION) === 'md');
    }
}
