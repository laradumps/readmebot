<?php

declare(strict_types = 1);

namespace App\Commannds;

use App\Commands\Concerns\Ui;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface, InputOption};
use Symfony\Component\Console\Output\OutputInterface;

final class DefaultCommand extends Command
{
    protected string $username;

    protected function configure(): void
    {
        $this->setName('default')
            ->setDescription('default command')
            ->setHidden(true);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Ui::logo();

        Ui::about();

        return Command::SUCCESS;
    }
}
