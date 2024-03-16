<?php

declare(strict_types = 1);

namespace Tests\Unit\Action;

use TCB\FlysystemSync\Action;
use TCB\FlysystemSync\Paths\File;
use Tests\Support\UnitTester;

class FileCest
{
    public function createAction(UnitTester $I): void
    {
        $action = Action::get(
            new File('path', 'public', 1, 100, 'json'),
            null
        );

        $I->assertEquals(Action::CREATE, $action, 'FILE with no TARGET is always ::CREATE');
    }

    public function updateAction(UnitTester $I): void
    {
        $action = Action::get(
            new File('path', 'public', 1, 100, 'json'),
            new File('path', 'public', 1, 101, 'json')
        );
        $I->assertEquals(Action::UPDATE, $action, 'FILEsS different lastModified ::UPDATE');
    }

    public function nothingAction(UnitTester $I): void
    {
        $action = Action::get(
            new File('path', 'public', 1, 100, 'json'),
            new File('path', 'public', 1, 100, 'json')
        );
        $I->assertEquals(Action::NOTHING, $action, 'FILEsS same ::NOTHING');
    }
}
