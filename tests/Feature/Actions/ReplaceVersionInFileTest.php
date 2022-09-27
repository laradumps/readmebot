<?php

declare(strict_types = 1);

use App\Actions\{CreateFakeVersion, ReplaceVersionInFile};
use App\File;
use App\ValueObjects\{FileLoaded, Version};

it('can replace version', function (Version $newVersion) {
    ReplaceVersionInFile::handle($newVersion, $this->file);

    expect(File::get($this->filePath))->toContain(strval($newVersion));
})->with([
    'random version 1' => [CreateFakeVersion::handle()],
    'random version 2' => [CreateFakeVersion::handle()],
]);

it('will throw exception when file has no tag', function (Version $newVersion) {
    File::delete($this->filePath);
    File::put($this->filePath, strval($newVersion));

    ReplaceVersionInFile::handle($newVersion, FileLoaded::open($this->filePath));
})->with([
    'random version 1' => [CreateFakeVersion::handle()],
    'random version 2' => [CreateFakeVersion::handle()],
])->throws('Could not find the current LaraDumps version.');

it('will throw exception when there is nothing to do', function (Version $newVersion) {
    File::delete($this->filePath);
    File::put($this->filePath, '<!--LaraDumpsVersion-->' . strval($newVersion) . '<!--EndOfLaraDumpsVersion-->');

    expect(fn () => ReplaceVersionInFile::handle($newVersion, FileLoaded::open($this->filePath)))
    ->toThrow("Nothing to update in file {$this->fileName}");
})->with([
    'random version 1' => [CreateFakeVersion::handle()],
    'random version 2' => [CreateFakeVersion::handle()],
]);

beforeEach(function () {
    $this->fileName = randomStr();
    $this->filePath = '/tmp/' . $this->fileName;

    File::put($this->filePath, fileContent());

    $this->file = FileLoaded::open($this->filePath);
});

afterEach(function () {
    File::delete($this->filePath);
});
