<?php

declare(strict_types = 1);

namespace App\ValueObjects;

use function Safe\json_decode;

/** Valid GitHub credential */
final class GithubCredential
{
    private string $name;

    private string $email;

    public static function fromString(string $credentials): self
    {
        $credentials = self::validateCredential($credentials);

        $githubCredential                     = new static();

        $githubCredential->name               = $credentials->name;  // @phpstan-ignore-line
        $githubCredential->email              = $credentials->email; // @phpstan-ignore-line

        return $githubCredential;
    }

    private static function validateCredential(string $credentials): object
    {
        try {
            $credentials = (object) json_decode($credentials);
        } catch (\Exception $e) {
            throw new \Exception('Github Credentials: Could not parse json.');
        }

        if (! isset($credentials->name) || ! isset($credentials->email)) {
            throw new \Exception('Github Credentials: name or email key missing.');
        }
        
        if (empty(trim($credentials->name))) {
            throw new \Exception('Github Credentials: Invalid GitHub name.');
        }
        
        //Must have a valid email address
        if (! filter_var($credentials->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Github Credentials: Invalid GitHub email.');
        }

        return $credentials;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }
}
