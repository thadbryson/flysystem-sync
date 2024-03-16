<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems\Reader;

use Tests\Support\UnitTester;
use Tests\Unit\Filesystems\ReaderTrait;

class GetFileCest
{
    use ReaderTrait;

    public function getFile(UnitTester $I): void
    {
        foreach (static::FILES as $filepath) {
            $file = $this->sync_filesystem->getFile($filepath);
            $this->assertFile($I, $file, $filepath);
        }
    }

    public function getFileNotFoundReturnsNull(UnitTester $I): void
    {
        foreach (static::FILES as $filepath) {
            $path = $this->sync_filesystem->getFile($filepath . '_nope');
            $I->assertNull($path);
        }

        foreach (static::DIRECTORIES as $dirpath) {
            $path = $this->sync_filesystem->getFile($dirpath . '_nope');
            $I->assertNull($path);
        }
    }

    public function getFileNotFileReturnsNull(UnitTester $I): void
    {
        foreach (static::DIRECTORIES as $dirpath) {
            $path = $this->sync_filesystem->getFile($dirpath . '_nope');
            $I->assertNull($path);
        }
    }
}
