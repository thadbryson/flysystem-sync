<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems\Reader;

use Tests\Support\UnitTester;
use Tests\Unit\Filesystems\ReaderTrait;

class GetDirectoryCest
{
    use ReaderTrait;

    public function getDirectory(UnitTester $I): void
    {
        foreach (static::DIRECTORIES as $dirpath) {
            $directory = $this->sync_filesystem->getDirectory($dirpath);
            $this->assertDirectory($I, $directory, $dirpath);
        }
    }

    public function getDirectoryNotFoundReturnsNull(UnitTester $I): void
    {
        foreach (static::FILES as $filepath) {
            $path = $this->sync_filesystem->getDirectory($filepath . '_nope');
            $I->assertNull($path);
        }

        foreach (static::DIRECTORIES as $dirpath) {
            $path = $this->sync_filesystem->getDirectory($dirpath . '_nope');
            $I->assertNull($path);
        }
    }

    public function getDirectoryNotDirectoryReturnsNull(UnitTester $I): void
    {
        foreach (static::FILES as $dirpath) {
            $path = $this->sync_filesystem->getDirectory($dirpath);
            $I->assertNull($path);
        }
    }
}
