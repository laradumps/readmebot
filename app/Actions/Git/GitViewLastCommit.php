<?php

declare(strict_types = 1);

namespace App\Actions\Git;

use App\Actions\RunCommand;

/** Fetch Last Git commit information */
final class GitViewLastCommit
{
    public static function handle(): object
    {
        try {
            $data = [
                'author'  => RunCommand::handle("git log -1 --pretty=format:'%an'"),
                'email'   => RunCommand::handle("git log -1 --pretty=format:'%ae'"),
                'message' => RunCommand::handle('git log -1 --pretty=%B | tr -d "\n\r"'),
            ];
        } catch (\Exception $e) {
            throw new \Exception('Could not fetch last commit data');
        }

        return (object) $data;
    }
}
