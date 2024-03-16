<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems\Reader;

use Tests\Support\UnitTester;
use Tests\Unit\Filesystems\ReaderTrait;

use function array_keys;
use function count;

class GetDirectoryContentsCest
{
    use ReaderTrait;

    public function contentFound(UnitTester $I): void
    {
        $contents = $this->sync_filesystem->getDirectoryContents('home/thad/Documents');

        $I->assertEquals(3, count($contents), '->getDirectoryContents() size');
        $I->assertEquals([
            'home/thad/Documents',
            'home/thad/Documents/taxes.md',
            'home/thad/Documents/.passwd',
        ], array_keys($contents));

        $this->assertDirectory($I, $contents['home/thad/Documents'], 'home/thad/Documents');

        $this->assertFile($I, $contents['home/thad/Documents/taxes.md'], 'home/thad/Documents/taxes.md');
        $this->assertFile($I, $contents['home/thad/Documents/.passwd'], 'home/thad/Documents/.passwd');
    }

    public function notFoundReturnsNull(UnitTester $I): void
    {
        $contents = $this->sync_filesystem->getDirectoryContents('home/thad/nada');
        $I->assertNull($contents);

        $contents = $this->sync_filesystem->getDirectoryContents('');
        $I->assertNull($contents);
    }

    public function onlyPartOfDirectoryName(UnitTester $I): void
    {
        // Real directory: home/thad/Music
        $contents = $this->sync_filesystem->getDirectoryContents('home/thad/Mus');
        $I->assertNull($contents);
    }

    public function caseSensitive(UnitTester $I): void
    {
        // Real directory: home/thad/Music
        $contents = $this->sync_filesystem->getDirectoryContents('home/thad/music');
        $I->assertNull($contents);
    }
}
