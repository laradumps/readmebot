<?php

declare(strict_types = 1);

use App\ValueObjects\Version;

it('properly creates a version object')
    ->expect(fn () => Version::fromString('1.2.3')->__toString())
    ->toBe('1.2.3');

it('properly fails with an invalid SemVer')
    ->tap(fn () => Version::fromString('v1.2.3'))
    ->throws('Invalid Semantic Version');
