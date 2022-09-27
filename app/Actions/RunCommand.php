<?php

declare(strict_types = 1);

namespace App\Actions;

use Safe\Exceptions\ExecException;
use function Safe\shell_exec;

/** Execute command via shell */
final class RunCommand
{
    /**
     *
     * @param string $command The command to execure
     * @param bool $isDryRun Echoes the command instead of executing it.
     * @return string Command or command result
     * @throws ExecException when command fails to run
     */
    public static function handle(string $command, bool $isDryRun = false): string
    {
        if ($isDryRun === true) {
            echo '----> ' . $command . PHP_EOL;

            return $command;
        }

        try {
            $result = shell_exec($command);
        } catch (\Exception $e) {
            $result = 'Error running command: ' . $e->getMessage();
        }

        return $result;
    }
}
