<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Sorter\Special;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Action;

class EmptyArraysGivenTest extends Unit
{
    public function testNoFiles(): void
    {
        $actions = new Action\Sorter([], []);

        $this->assertEquals([], $actions->create_files);
        $this->assertEquals([], $actions->update_files);
        $this->assertEquals([], $actions->delete_files);

        $this->assertEquals([], $actions->create_directories);
        $this->assertEquals([], $actions->update_directories);
        $this->assertEquals([], $actions->delete_directories);
    }
}
