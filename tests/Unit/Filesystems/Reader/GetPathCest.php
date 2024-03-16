<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems\Reader;

use Tests\Support\UnitTester;
use Tests\Unit\Filesystems\ReaderTrait;

class GetPathCest
{
    use ReaderTrait;

    public function getPath(UnitTester $I): void
    {
        foreach (static::FILES as $filepath) {
            $path = $this->sync_filesystem->getPath($filepath);
            $this->assertFile($I, $path, $filepath);
        }

        foreach (static::DIRECTORIES as $dirpath) {
            $path = $this->sync_filesystem->getPath($dirpath);
            $this->assertDirectory($I, $path, $dirpath);
        }
    }

    public function getPathNotFoundReturnsNull(UnitTester $I): void
    {
        foreach (static::FILES as $filepath) {
            $path = $this->sync_filesystem->getPath($filepath . '_nope');
            $I->assertNull($path);
        }

        foreach (static::DIRECTORIES as $dirpath) {
            $path = $this->sync_filesystem->getPath($dirpath . '_nope');
            $I->assertNull($path);
        }
    }
}
