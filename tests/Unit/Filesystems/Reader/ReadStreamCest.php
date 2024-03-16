<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems\Reader;

use Tests\Support\TestAdapter;
use Tests\Support\UnitTester;
use Tests\Unit\Filesystems\ReaderTrait;

class ReadStreamCest
{
    use ReaderTrait;

    public function readStream(UnitTester $I): void
    {
        foreach (static::FILES as $path) {
            $contents = $this->sync_filesystem->readStream($path);
            $I->assertEquals(TestAdapter::DEFAULT_CONTENTS, $contents);
        }
    }
}
