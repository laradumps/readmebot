<?php

declare(strict_types = 1);

use App\Actions\RunCommand;

/**
 * ❗ NOTE: These tests must be executed in isolation since the RunComand::class mocking is persistent.
 *    Run: pest --group=not-mocked
 */

it('can run a shell command and return result', function () {
    expect(RunCommand::handle('whoami'))->toBe(shell_exec('whoami'));
})
->group('not-mocked')
->requireGroupToBeCalled('not-mocked');

test('properly applies dry run mode', function () {
    RunCommand::handle('whoami', true);
})
->group('not-mocked')
->requireGroupToBeCalled('not-mocked')
->expectOutputString('----> whoami' . PHP_EOL);
