<?php

declare(strict_types = 1);

use App\Actions\CreateFakeVersion;
use App\ValueObjects\Version;

it('generates a fake version', function () {
    $version = CreateFakeVersion::handle();
    
    expect($version)->toBeInstanceOf(Version::class);
    
    expect($version->__toString())->toBeSemVer();
});
