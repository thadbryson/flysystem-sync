<?php

declare(strict_types = 1);

namespace Tests\Unit\Helpers\PathHelper;

use Codeception\Attribute\DataProvider;
use TCB\FlysystemSync\Helpers\PathHelper;
use Tests\Support\UnitTester;

class PathHelperCest
{
    #[DataProvider('providerPreparePath')]
    public function preparePathBasic(UnitTester $I, \Codeception\Example $example): void
    {
        $path = PathHelper::prepare($example[0]);

        $I->assertEquals('path', $path);
    }

    protected function providerPreparePath(): array
    {
        return [
            ['path'],
            ['/path/'],

            ['     path      '],
            ['    path'],
            ['path  '],

            ['    path/      '],
            ['   path/'],
            ['path/    '],

            ['   /path/   '],
            ['   /path/'],
            ['/path/   '],
        ];
    }

    #[DataProvider('providerPrepareBackslashes')]
    public function preparePathBackslashes(UnitTester $I, \Codeception\Example $example): void
    {
        $path = PathHelper::prepare($example[0]);

        $I->assertEquals($example[1], $path);
    }

    public function providerPrepareBackslashes(): array
    {
        return [
            ["\\home/to\\nowhere/Where\\", 'home/to/nowhere/Where'],

            ["\\home\\Here", 'home/Here'],
            ['home\\here2', 'home/here2'],
            ['\\home\\here3\\', 'home/here3'],
        ];
    }
}
