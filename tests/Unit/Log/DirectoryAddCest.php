<?php

declare(strict_types = 1);

namespace Tests\Unit\Log;

use TCB\FlysystemSync\Log;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Runners\DirectoryRunner;
use Tests\Support\UnitTester;

class DirectoryAddCest
{
    public function logNoDifferences(UnitTester $I): void
    {
        $source = new Directory('home/thad', 'public', 1_000_000);
        $target = new Directory('home/thad', 'public', 1_000_000);

        $log = new Log('home/thad', DirectoryRunner::class);
        $log
            ->before($source, $target)
            ->after($source, $target)
            ->final($source, $target);

        $expected = [
            'source'      => $source->toArray(),
            'target'      => $target->toArray(),
            'differences' => [],
        ];

        $I->assertEquals($expected, $log->getBefore());
        $I->assertEquals($expected, $log->getAfter());
        $I->assertEquals($expected, $log->getFinal());

        $I->assertTrue($log->isCorrect());
    }

    public function logWithDifferences(UnitTester $I): void
    {
        $source = new Directory('home/thad', 'public', 1_000_000);
        $target = new Directory('home/thad', 'private', 1_000_000);

        $log = new Log('home/thad', DirectoryRunner::class);
        $log
            ->before($source, $target)
            ->after($source, $target)
            ->final($source, $target);

        $expected = [
            'source'      => $source->toArray(),
            'target'      => $target->toArray(),
            'differences' => [
                'visibility' => 'public',
            ],
        ];

        $I->assertEquals($expected, $log->getBefore());
        $I->assertEquals($expected, $log->getAfter());
        $I->assertEquals($expected, $log->getFinal());

        $I->assertFalse($log->isCorrect());
    }
}
