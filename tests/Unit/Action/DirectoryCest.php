<?php

declare(strict_types = 1);

namespace Tests\Unit\Action;

use TCB\FlysystemSync\Action;
use TCB\FlysystemSync\Paths\Directory;
use Tests\Support\UnitTester;

class DirectoryCest
{
    public function createAction(UnitTester $I): void
    {
        $action = Action::get(
            new Directory('path', 'public', 1),
            null
        );
        $I->assertEquals(Action::CREATE, $action, 'DIRECTORY with no TARGET is always ::CREATE');
    }

    public function updateAction(UnitTester $I): void
    {
        $action = Action::get(
            new Directory('path', 'public', 1),
            new Directory('path', 'private', 1)
        );
        $I->assertEquals(Action::UPDATE, $action, 'DIRECTORY different visibility ::UPDATE');
    }

    public function nothingAction(UnitTester $I): void
    {
        $action = Action::get(
            new Directory('path', 'public', 1),
            new Directory('path', 'public', 1)
        );
        $I->assertEquals(Action::NOTHING, $action, 'DIRECTORY same ::NOTHING');

        $action = Action::get(
            new Directory('path', 'public', 1),
            new Directory('path', 'public', 10)
        );
        $I->assertEquals(Action::NOTHING, $action, 'DIRECTORY only difference lastModified ::NOTHING');
    }
}
