<?php

declare(strict_types = 1);

namespace App\Commands\Concerns;

final class Auth
{
    private const KEY = '$2y$10$.IyIx1RsAe0Btcfx2fdf2ustynG02x1BZm9HKFASDFq1i8kBvKwsW';

    public static function check(): void
    {
        if (password_verify(strval(getenv('READMEBOT_KEY')), self::KEY) === false) {
            Ui::error('You need the password key to proceeed.');
            exit(1);
        }
    }
}
