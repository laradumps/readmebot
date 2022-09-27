<?php

declare(strict_types = 1);

namespace App\Actions;

use Exception;
use function Safe\preg_match;

/**
 * Validate a SemVer
 * @see #https://ihateregex.io/expr/semver/
 */
final class ValidateSemVer
{
    public static function handle(string $version): string
    {
        $regex = '/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/m';

        if (preg_match($regex, $version, $matches) === 0) {
            throw new Exception('Invalid Semantic Version');
        }
 
        return $version;
    }
}
