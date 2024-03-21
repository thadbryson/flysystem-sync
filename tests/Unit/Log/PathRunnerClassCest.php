<?php

declare(strict_types = 1);

namespace Tests\Unit\Log;

use TCB\FlysystemSync\Log;
use TCB\FlysystemSync\Runners\DirectoryRunner;
use TCB\FlysystemSync\Runners\FileRunner;
use Tests\Support\UnitTester;

class PathRunnerClassCest
{
    public function pathRunnerClass(UnitTester $I): void
    {
        $file = new Log('home/thad/README.md', FileRunner::class);

        $I->assertEquals('home/thad/README.md', $file->source);
        $I->assertEquals(FileRunner::class, $file->runner);

        $directory = new Log('home/thad/Music', DirectoryRunner::class);

        $I->assertEquals('home/thad/Music', $directory->source);
        $I->assertEquals(DirectoryRunner::class, $directory->runner);
    }
}
