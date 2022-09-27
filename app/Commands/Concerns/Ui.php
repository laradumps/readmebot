<?php

declare(strict_types = 1);

namespace App\Commands\Concerns;

use Symfony\Component\Console\Output\ConsoleOutput;

final class Ui
{
    public function __construct(string $message = '')
    {
        $output = new ConsoleOutput();
        
        $output->setDecorated(false);

        $output->writeln($message);
    }

    /** Display success styled message */
    public static function ok(string $message): void
    {
        new self(PHP_EOL . "\033[37;42;1m  ✓ OK   \033[39;49;22m {$message}" . PHP_EOL);
    }

    /** Display error styled message */
    public static function error(string $message): void
    {
        new self(PHP_EOL . "\033[37;41;1m ✘ ERROR \033[39;49;22m {$message}" . PHP_EOL);
    }

    /** Display error info message */
    public static function info(string $message): void
    {
        new self(PHP_EOL . "\033[37;48;2;96;165;250;1m ℹ INFO  \033[39;49;22m {$message}" . PHP_EOL);
    }

    /** Display Logo */
    public static function logo(): void
    {
        (new self(<<<EOT
            \033[38;2;234;179;8m
             _                     _____                            
            | |                   |  __ \                           
            | |     __ _ _ __ __ _| |  | |_   _ _ __ ___  _ __  ___ 
            | |    / _  |  __/ _  | |  | | | | |  _   _ \|  _ \/ __|
            | |___| (_| | | | (_| | |__| | |_| | | | | | | |_) \__ \
            |______\__,_|_|  \__,_|_____/ \__,_|_| |_| |_| .__/|___/
                                                         | |     
                                                         |_|
           \033[48;5;178m                 README BOT                 \033[0;33m\033[00m
        
        EOT));
    }

    /** Display about message */
    public static function about(): void
    {
        (new self(<<<EOT
            👋 Hi, I am the  \033[38;2;234;179;8mLaraDumps README-BOT\e[0m and I am in charge of maintaining LaraDumps repositories up to date.

            Read more at: \033[38;2;234;179;8mhttps://github.com/laradumps/readmebot/ \e[0m
            
        EOT));
    }
}
