<?php

declare(strict_types = 1);

use App\ValueObjects\GithubCredential;

it('properly parses a credential from string', function () {
    expect(GithubCredential::fromString('{"name":"foo","email":"foo@bar.baz"}'))
        ->name()->toBe('foo')
        ->email()->toBe('foo@bar.baz');
});

it('rejects a malformed credential string', function (string $exceptionMessage, string $credentials) {
    expect(fn () => GithubCredential::fromString($credentials))
        ->toThrow(\Exception::class, $exceptionMessage);
})->with(
    [
        'wrong json'                    => ['Github Credentials: Could not parse json.', 'name: foo'],
        'undefined name and email keys' => ['Github Credentials: name or email key missing.', '{"foo":"","bar":""}'],
        'name and email missing'        => ['Github Credentials: Invalid GitHub name.', '{"name":"","email":""}'],
        'name missing'                  => ['Github Credentials: Invalid GitHub name.', '{"name":"","email":"foo@bar.baz"}'],
        'email missing'                 => ['Github Credentials: Invalid GitHub email.', '{"name":"foo","email":""}'],
        'email is invalid'              => ['Github Credentials: Invalid GitHub email.', '{"name":"foo","email":"foo@"}'],
    ]
);
