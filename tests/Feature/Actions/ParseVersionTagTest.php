<?php

declare(strict_types = 1);

use App\Actions\ParseVersionTag;

it('properly parses versions', function () {
    expect(ParseVersionTag::handle(fileContent()))->toHaveCount(2)
        ->sequence(
            fn ($version) => $version->__toString()->toBe('1.2.3'),
            fn ($version) => $version->__toString()->toBe('1.2.4'),
        );
});
