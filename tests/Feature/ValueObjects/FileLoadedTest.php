<?php

declare(strict_types = 1);

use App\File;
use App\ValueObjects\FileLoaded;

beforeEach(function () {
    $this->filename     = randomStr();
    $this->fileContent  = randomStr();
    $this->filePath     = '/tmp/' . $this->filename;

    File::put($this->filePath, $this->fileContent);
});

afterEach(function () {
    if (File::exists($this->filePath)) {
        File::delete($this->filePath);
    }
});

test('it loads an existing file', function () {
    expect(FileLoaded::open($this->filePath))
        ->toBeInstanceOf(FileLoaded::class)
        ->filename()->toBe($this->filename)
        ->filePath()->toBe($this->filePath)
        ->fileContent()->toBe($this->fileContent);
});

test('updates file content', function () {
    $file = FileLoaded::open($this->filePath);

    $file = $file->updateContent('hello world');

    expect($file)->fileContent()->toBe('hello world');
});

test('it fails when file does not exist', function () {
    FileLoaded::open('/tmp/p0t63jVkJvjZm3E3JFBf');
})->throws('File /tmp/p0t63jVkJvjZm3E3JFBf doesn\'t exist');
