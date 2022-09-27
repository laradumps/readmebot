<?php

declare(strict_types = 1);

use App\Actions\Git\GitChangedFiles;
use App\Actions\RunCommand;

it('finds only .MD changed files', function () {
    $changedFiles = <<<EOT
        README.md
        doc/get-started/INSTALL.md
        index.php
        EOT;

    $getChangedFiles = \Mockery::mock('overload:' . RunCommand::class);

    $getChangedFiles->shouldReceive('handle')
            ->with('git status -s | grep -E "^ M|^\?\?" | awk \'{print $2}\'')
            ->once()
            ->andReturn($changedFiles);

    $this->assertEquals(['README.md', 'doc/get-started/INSTALL.md'], GitChangedFiles::handle());

    \Mockery::close();
});
