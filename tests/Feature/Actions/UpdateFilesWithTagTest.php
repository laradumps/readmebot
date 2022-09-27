<?php

declare(strict_types = 1);

use App\Actions\{CreateFakeVersion, UpdateFilesWithTag};
use App\File;
use App\ValueObjects\{ExcludeFileList, Version};

it('throws exception when nothing is found', function () {
    $newVersion = CreateFakeVersion::handle();
    
    UpdateFilesWithTag::handle($newVersion, $this->excludeFileList, '<\!--LxrxDuxpsVxrsx0n-->');
})
->skip('different behavior in CI')
->group('not-mocked')
->requireGroupToBeCalled('not-mocked')
->throws(Exception::class, 'Searching LaraDumpsVersion tag: An error occured');

it('can replace version in files', function (Version $newVersion) {
    File::put($this->filePath, '<!--LaraDumpsVersion-->2.73.4<!--EndOfLaraDumpsVersion-->');

    UpdateFilesWithTag::handle($newVersion, $this->excludeFileList, '<\!--LaraDumpsVersion-->');

    expect(File::get($this->filePath))->not->toContain('2.73.4');
})
->group('not-mocked')
->requireGroupToBeCalled('not-mocked')
->with([
    'random version 1' => [CreateFakeVersion::handle()],
    'random version 2' => [CreateFakeVersion::handle()],
]);

beforeEach(function () {
    $this->filePath        = './' . randomStr() . '.md';
    $this->excludeFileList = ExcludeFileList::fromString('README.md');
});

afterEach(function () {
    File::delete($this->filePath);
});
