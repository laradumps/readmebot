<?php

declare(strict_types = 1);

use App\Actions\Git\GitCommitChanges;
use App\Actions\RunCommand;
use App\ValueObjects\GithubCredential;

test('it can commit the last changes', function () {
    $changedFile        = 'README.md';
    $gitCredentials     = GithubCredential::fromString('{"name":"foo","email":"foo@bar.baz"}');
    $commitMessage      = 'changing version!';

    // Mock changed files
    $mock = \Mockery::mock('overload:' . RunCommand::class);

    $mock->shouldReceive('handle')
            ->with('git status -s | grep -E "^ M|^\?\?" | awk \'{print $2}\'')
            ->once()
            ->andReturn($changedFile);

    // Mock Git settings
    $mock->shouldReceive('handle')
            ->with("git config --global user.name \"{$gitCredentials->name()}\"", false)
            ->once()
            ->andReturn(PHP_EOL);

    $mock->shouldReceive('handle')
            ->with("git config --global user.email \"{$gitCredentials->email()}\"", false)
            ->once()
            ->andReturn(PHP_EOL);

    $mock->shouldReceive('handle')
            ->with('git add "README.md"', false)
            ->once()
            ->andReturn(PHP_EOL);

    $mock->shouldReceive('handle')
            ->with("git add '{$changedFile}'", false)
            ->once()
            ->andReturn('');

    $mock->shouldReceive('handle')
            ->with("git commit -m '✓ {$commitMessage}'", false)
            ->once()
            ->andReturn('');

    $mock->shouldReceive('handle')
            ->with('git push', false)
            ->once()
            ->andReturn('');

    $this->assertTrue(GitCommitChanges::handle($commitMessage, $gitCredentials));

    \Mockery::close();
});
