<?php

declare(strict_types = 1);

namespace App\Commannds;

use App\Actions\Git\GitCommitChanges;
use App\Actions\{CreateFakeVersion, UpdateFilesWithTag};
use App\Commands\Concerns\{Auth, Ui};
use App\ValueObjects\{ExcludeFileList, GithubCredential, Version};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface, InputOption};
use Symfony\Component\Console\Output\OutputInterface;

final class UpdateVersionCommand extends Command
{
    protected string $commitMessage;

    protected bool $doNotCommit = true;

    protected Version $newVersion;

    protected InputInterface $input;
   
    protected OutputInterface $output;
    
    protected excludeFileList $excludeFileList;

    protected function configure(): void
    {
        $this->setName('update-version')
            ->setDescription('Update version on readme')
            ->addOption(
                'new-version',
                '',
                InputOption::VALUE_REQUIRED,
                'Version to update to',
            )
            ->addOption(
                'fake-version',
                '',
                InputOption::VALUE_NONE,
                'Use fake data',
            )
            ->addOption(
                'exclude-files',
                '',
                InputOption::VALUE_OPTIONAL,
                'Do not replace in these files',
            )
            ->addOption(
                'no-commit',
                '',
                InputOption::VALUE_NONE,
                'Do not commit changes',
            )
            ->addOption(
                'github-credential',
                '',
                InputOption::VALUE_REQUIRED,
                'GitHub Crendential {"username":"foo","email":"foo@bar.com"}',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input       = $input;
        $this->output      = $output;
        $this->doNotCommit = boolval($input->getOption('no-commit'));

        Ui::logo();

        Auth::check();

        Ui::info('Starting updating process...');

        $this->excludeFileList = ExcludeFileList::fromString(strval($this->input->getOption('exclude-files')));
        
        $this->version();
        
        $this->commitMessage = "version updated to {$this->newVersion}";

        $this->updateFiles();

        $this->commit();
        
        Ui::ok($this->commitMessage);

        return Command::SUCCESS;
    }
    
    private function version(): void
    {
        // Version must be always informed
        if (boolval($this->input->getOption('new-version')) === false && boolval($this->input->getOption('fake-version')) === false) {
            $this->fail('You need to provide the --new-version or set --fake-version for testing.');
        }
        
        //User requested a fake version
        if (boolval($this->input->getOption('fake-version')) === true) {
            $this->newVersion = CreateFakeVersion::handle();

            return;
        }

        try {
            $this->newVersion =  Version::fromString(strval($this->input->getOption('new-version')));
        } catch (\Exception $e) {
            $this->fail('New Version: ' . $e->getMessage());
        }
    }
   
    private function updateFiles(): void
    {
        try {
            UpdateFilesWithTag::handle(
                newVersion: $this->newVersion,
                excludeFileList: $this->excludeFileList
            );
        } catch (\Exception $e) {
            $this->fail('Updating files: ' . $e->getMessage());
        }
    }

    private function commit(): void
    {
        try {
            $githubCredential = GithubCredential::fromString(strval($this->input->getOption('github-credential')));

            GitCommitChanges::handle(
                $this->commitMessage,
                credential: $githubCredential,
                isDryRun: $this->doNotCommit
            );
        } catch (\Exception $e) {
            $this->fail('Commit: ' . $e->getMessage());
        }
    }

    private function fail(string $message): void
    {
        Ui::error($message);

        exit(Command::FAILURE);
    }
}
