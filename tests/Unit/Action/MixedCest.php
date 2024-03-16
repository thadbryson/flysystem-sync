<?php

declare(strict_types = 1);

namespace Tests\Unit\Action;

use TCB\FlysystemSync\Action;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;
use Tests\Support\UnitTester;

class MixedCest
{
    public function updateAction(UnitTester $I): void
    {
        $action = Action::get(
            new File('path', 'public', 1, 100, 'json'),
            new Directory('path', 'private', 1)

        );
        $I->assertEquals(Action::UPDATE, $action, 'FILE / DIRECTORY');

        $action = Action::get(
            new Directory('path', 'public', 1),
            new File('path', 'public', 1, 101, 'json')
        );
        $I->assertEquals(Action::UPDATE, $action, 'DIRECTORY / FILE');
    }
}
