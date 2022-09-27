<?php

declare(strict_types = 1);

namespace Tests\Feature\Actions;

use App\Actions\Git\GitViewLastCommit;
use App\Actions\RunCommand;

it('can get the last commit', function () {
    $fakeCommit = (object) [
        'author'  => 'Foo Bar',
        'email'   => 'foo@bar.test',
        'message' => 'foo bar baz',
    ];

    $getInfo = \Mockery::mock('overload:' . RunCommand::class);

    $getInfo->shouldReceive('handle')
            ->with("git log -1 --pretty=format:'%an'")
            ->once()
            ->andReturn($fakeCommit->author);

    $getInfo->shouldReceive('handle')
            ->with("git log -1 --pretty=format:'%ae'")
            ->once()
            ->andReturn($fakeCommit->email);

    $getInfo->shouldReceive('handle')
            ->with('git log -1 --pretty=%B | tr -d "\n\r"')
            ->once()
            ->andReturn($fakeCommit->message);

    $this->assertEquals($fakeCommit, GitViewLastCommit::handle());

    \Mockery::close();
});
