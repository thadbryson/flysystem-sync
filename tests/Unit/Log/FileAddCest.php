<?php

declare(strict_types = 1);

namespace Tests\Unit\Log;

use TCB\FlysystemSync\Log;
use TCB\FlysystemSync\Paths\File;
use TCB\FlysystemSync\Runners\FileRunner;
use Tests\Support\UnitTester;

class FileAddCest
{
    public function logNoDifferences(UnitTester $I): void
    {
        $source = new File('home/thad', 'public', 1_000_000, 5_000, 'text/json');
        $target = new File('home/thad', 'public', 1_000_000, 5_000, 'text/json');

        $log = new Log('home/thad', FileRunner::class);
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
        $source = new File('home/thad', 'public', 1_000_000, 5_000, 'text/json');
        $target = new File('home/thad', 'private', 1_000_000, 3_200, 'text/json');

        $log = new Log('home/thad', FileRunner::class);
        $log
            ->before($source, $target)
            ->after($source, $target)
            ->final($source, $target);

        $expected = [
            'source'      => $source->toArray(),
            'target'      => $target->toArray(),
            'differences' => [
                'visibility' => 'public',
                'fileSize'   => 5_000,
            ],
        ];

        $I->assertEquals($expected, $log->getBefore());
        $I->assertEquals($expected, $log->getAfter());
        $I->assertEquals($expected, $log->getFinal());

        $I->assertFalse($log->isCorrect());
    }
}
