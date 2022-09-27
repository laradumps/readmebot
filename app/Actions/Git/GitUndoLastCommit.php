<?php

declare(strict_types = 1);

namespace App\Actions\Git;

use App\Actions\RunCommand;

/** Undo last commit */
final class GitUndoLastCommit
{
    public static function handle(): bool
    {
        try {
            RunCommand::handle('git reset --soft HEAD~1');
        } catch (\Exception $e) {
            throw new \Exception('Could not undo last commit (' . $e->getMessage() . ')');
        }

        return true;
    }
}
