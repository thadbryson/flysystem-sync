<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems\Reader;

use Tests\Support\UnitTester;
use Tests\Unit\Filesystems\ReaderTrait;

class GetFileReturnsNullCest
{
    use ReaderTrait;

    public function getFile(UnitTester $I): void
    {
        foreach (static::FILES as $filepath) {
            $file = $this->sync_filesystem->getFile($filepath . '_nope');
            $path = $this->sync_filesystem->getPath($filepath . '_nope');

            $I->assertNull($file);
            $I->assertNull($path);
        }
    }

    public function getDirectory(UnitTester $I): void
    {
        foreach (static::DIRECTORIES as $dirpath) {
            $directory = $this->sync_filesystem->getDirectory($dirpath . '_nope');
            $path      = $this->sync_filesystem->getPath($dirpath . '_nope');

            $I->assertNull($directory);
            $I->assertNull($path);
        }
    }
}
