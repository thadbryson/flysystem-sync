<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\ActionHelper\Special;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Helper\ActionHelper;

class EmptyArraysGivenTest extends Unit
{
    public function testNoFiles(): void
    {
        $actions = new ActionHelper([], []);

        $this->assertEquals([], $actions->create_files);
        $this->assertEquals([], $actions->update_files);
        $this->assertEquals([], $actions->delete_files);

        $this->assertEquals([], $actions->create_directories);
        $this->assertEquals([], $actions->update_directories);
        $this->assertEquals([], $actions->delete_directories);
    }
}
