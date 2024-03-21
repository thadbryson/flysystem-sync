<?php

declare(strict_types = 1);

namespace Tests\Unit\Log;

use TCB\FlysystemSync\Log;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Runners\DirectoryRunner;
use Tests\Support\UnitTester;

class StageNoDifferencesCest
{
    public function isNotStarted(UnitTester $I): void
    {
        $log = new Log('home/thad', DirectoryRunner::class);

        $I->assertFalse($log->isBefore());
        $I->assertFalse($log->isAfter());
        $I->assertFalse($log->isFinished());

        $I->assertFalse($log->isCorrect());
    }

    public function isBefore(UnitTester $I): void
    {
        $source = new Directory('home/thad', 'public', 1_000_000);
        $target = new Directory('home/thad', 'public', 1_000_000);

        $log = new Log('home/thad', DirectoryRunner::class);
        $log->before($source, $target);

        $I->assertTrue($log->isBefore());
        $I->assertFalse($log->isAfter());
        $I->assertFalse($log->isFinished());

        $I->assertFalse($log->isCorrect());
    }

    public function isAfter(UnitTester $I): void
    {
        $source = new Directory('home/thad', 'public', 1_000_000);
        $target = new Directory('home/thad', 'public', 1_000_000);

        $log = new Log('home/thad', DirectoryRunner::class);
        $log
            ->before($source, $target)
            ->after($source, $target);

        $I->assertTrue($log->isBefore());
        $I->assertTrue($log->isAfter());
        $I->assertFalse($log->isFinished());

        $I->assertFalse($log->isCorrect());
    }

    public function isFinished(UnitTester $I): void
    {
        $source = new Directory('home/thad', 'public', 1_000_000);
        $target = new Directory('home/thad', 'public', 1_000_000);

        $log = new Log('home/thad', DirectoryRunner::class);
        $log
            ->before($source, $target)
            ->after($source, $target)
            ->final($source, $target);

        $I->assertTrue($log->isBefore());
        $I->assertTrue($log->isAfter());
        $I->assertTrue($log->isFinished());

        $I->assertTrue($log->isCorrect());
    }
}
