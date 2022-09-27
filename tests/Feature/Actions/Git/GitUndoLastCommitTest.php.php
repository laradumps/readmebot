<?php

declare(strict_types = 1);

use App\Actions\Git\GitUndoLastCommit;
use App\Actions\RunCommand;

it('can undo last commit', function () {
    $getChangedFiles = \Mockery::mock('overload:' . RunCommand::class);

    $getChangedFiles->shouldReceive('handle')
            ->with('git reset --soft HEAD~1')
            ->once()
            ->andReturn(PHP_EOL);

    $this->assertTrue(GitUndoLastCommit::handle());

    \Mockery::close();
});
