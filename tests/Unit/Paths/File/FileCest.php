<?php

declare(strict_types = 1);

namespace Tests\Unit\Paths\File;

use TCB\FlysystemSync\Paths\File;
use Tests\Support\UnitTester;

class FileCest
{
    public function fileBasics(UnitTester $I): void
    {
        $file = new File(
            "\\home/is\\where/THE/heart/IS\\\\///",
            'public',
            100_000_000,
            5_000,
            'application/json'
        );

        $I->assertEquals('home/is/where/THE/heart/IS', $file->path);
        $I->assertEquals('public', $file->visibility);
        $I->assertEquals(100_000_000, $file->lastModified);
        $I->assertEquals(5_000, $file->fileSize);
        $I->assertEquals('application/json', $file->mimeType);

        $I->assertTrue($file->isFile());
        $I->assertFalse($file->isDirectory());

        $I->assertEquals([
            'path'         => 'home/is/where/THE/heart/IS',
            'type'         => File::class,
            'visibility'   => 'public',
            'lastModified' => 100_000_000,
            'fileSize'     => 5_000,
            'mimeType'     => 'application/json',
        ], $file->toArray());
    }
}
