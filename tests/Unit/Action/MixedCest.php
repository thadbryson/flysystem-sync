<?php

declare(strict_types = 1);

namespace Tests\Unit\Action;

use TCB\FlysystemSync\Helpers\ActionEnum;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;
use Tests\Support\UnitTester;

class MixedCest
{
    public function updateAction(UnitTester $I): void
    {
        $action = ActionEnum::get(
            new File('path', 'public', 1, 100, 'json'),
            new Directory('path', 'private', 1)

        );
        $I->assertEquals(ActionEnum::UPDATE, $action, 'FILE / DIRECTORY');

        $action = ActionEnum::get(
            new Directory('path', 'public', 1),
            new File('path', 'public', 1, 101, 'json')
        );
        $I->assertEquals(ActionEnum::UPDATE, $action, 'DIRECTORY / FILE');
    }
}
