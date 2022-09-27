<?php

declare(strict_types = 1);

use App\Actions\ValidateSemVer;

it('accepts proper SemVer strings ', function (string $version) {
    expect(ValidateSemVer::handle($version))
        ->toBe($version);
})->with(
    [
        '0.0.4',
        '1.2.3',
        '10.20.30',
        '1.1.2-prerelease+meta',
        '1.1.2+meta',
        '1.0.0-alpha',
        '1.0.0-alpha.beta',
        '1.0.0-alpha.1',
        '1.0.0-alpha.0valid',
        '1.0.0-rc.1+build.1',
        '1.2.3-beta',
        '10.2.3-DEV-SNAPSHOT',
        '1.2.3-SNAPSHOT-123',
        '1.0.0',
        '2.0.0+build.1848',
        '2.0.1-alpha.1227',
        '1.0.0-alpha+beta',
        '1.2.3----RC-SNAPSHOT.12.9.1--.12+788',
        '1.2.3----R-S.12.9.1--.12+meta',
    ]
);

it('rejects invalid SemVer strings', function (string $version) {
    ValidateSemVer::handle($version);
})->with(
    [
        '',
        'v1.2.3',
        'version 1.0',
        '1',
        '1.2',
        '01.1.1',
        '9.8.7-whatever+meta+meta',
        '1.2.3.DEV',
        '1.2.3-0123',
        '1.0.0-alpha_beta',
        '1.2-SNAPSHOT',
        '1.2.31.2.3----RC-SNAPSHOT.12.09.1--..12+788',
    ]
)->throws('Invalid Semantic Version');
