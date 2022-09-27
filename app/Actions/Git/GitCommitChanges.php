<?php

declare(strict_types = 1);

namespace App\Actions\Git;

use App\Actions\RunCommand;
use App\ValueObjects\GithubCredential;

/** Commit changed files */
final class GitCommitChanges
{
    private static bool $isDryRun;

    /**
     *
     * @param bool $isDryRun When true does not commit files
     * @throws \Exception when there is no files to commit
     */
    public static function handle(string $commitMessage, GithubCredential $credential, bool $isDryRun = false): bool
    {
        self::$isDryRun = $isDryRun;

        $files        =  GitChangedFiles::handle();

        if (count($files) > 0) {
            //Prepare files to be commited
            array_walk($files, fn (&$file) => $file = "\"{$file}\"");
            
            $files =  implode(' ', $files);
            
            RunCommand::handle('git config --global user.name "' . $credential->name() . '"', self::$isDryRun);
            
            RunCommand::handle('git config --global user.email "' . $credential->email() . '"', self::$isDryRun);

            RunCommand::handle('git add ' . $files, self::$isDryRun);
                       
            RunCommand::handle("git commit -m '✓ {$commitMessage}'", self::$isDryRun);

            RunCommand::handle('git push', self::$isDryRun);
     
            return true;
        }

        return false;
    }
}
