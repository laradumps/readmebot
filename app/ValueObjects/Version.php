<?php

declare(strict_types = 1);

namespace App\ValueObjects;

use App\Actions\ValidateSemVer;

/** Valid SemVer */
final class Version
{
    private string $version;

    public static function fromString(string $semVer): self
    {
        $version          = new static();
        $version->version = ValidateSemVer::handle($semVer);

        return $version;
    }

    public function __toString(): string
    {
        return $this->version;
    }
}
