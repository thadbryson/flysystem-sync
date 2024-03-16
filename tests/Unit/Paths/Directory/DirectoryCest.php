<?php

declare(strict_types = 1);

namespace Tests\Unit\Paths\Directory;

use TCB\FlysystemSync\Paths\Directory;
use Tests\Support\UnitTester;

class DirectoryCest
{
    public function directoryBasics(UnitTester $I): void
    {
        $directory = new Directory(
            "directory/is\\here/",
            'thadbry',
            7_500_000,
        );

        $I->assertEquals('directory/is/here', $directory->path);
        $I->assertEquals('thadbry', $directory->visibility);
        $I->assertEquals(7_500_000, $directory->lastModified);

        $I->assertFalse($directory->isFile());
        $I->assertTrue($directory->isDirectory());

        $I->assertEquals([
            'path'         => 'directory/is/here',
            'visibility'   => 'thadbry',
            'lastModified' => 7_500_000,
        ], $directory->toArray());
    }
}
