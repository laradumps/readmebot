<?php

declare(strict_types = 1);

it('does not start the bot without a key', function (array $command) {
    $result = shell_exec('READMEBOT_KEY=1234 php ./bin/readmebot update-version --no-commit ' . implode(' ', $command));
    
    expect($result)->toContain('You need the password key to proceeed');
})
->with([
    'all ok + fake-version' => [['--fake-version',  '--github-credential=\'{"name":"foo","email":"foo@bar.com"}\''], 'version updated to'],
]);

it('can update version', function (array $settings, string $message) {
    expect(getenv('READMEBOT_KEY'))->not->toBeFalse();

    $result = shell_exec('php ./bin/readmebot update-version --exclude-files=\'README.md\' --no-commit ' . implode(' ', $settings));
    
    expect($result)->toContain($message);
})
->requiresAuthentication()
->with(
    [
        'all ok + version'      => [['--new-version=0.0.3',  '--github-credential=\'{"name":"foo","email":"foo@bar.com"}\''], 'version updated to'],
        'all ok + fake-version' => [['--fake-version',  '--github-credential=\'{"name":"foo","email":"foo@bar.com"}\''], 'version updated to'],
        'no version'            => [['--github-credential=\'{"name":"foo","email":"foo@bar.com"}\''], 'You need to provide the --new-version or set --fake-version for testing.'],
        'bad version'           => [['--new-version=1',  '--github-credential=\'{"name":"foo","email":"foo@bar.com"}\''], 'New Version: Invalid Semantic Version'],
        'bad github'            => [['--fake-version',  '--github-credential=\'{"x":"foo","y":"foo@bar.com"}\''], 'Github Credentials: name or email key missing.'],
    ]
);
