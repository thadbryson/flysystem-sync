<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Bag\Special;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Action;

class NullValuesPassedTest extends Unit
{
    public function testNoFiles(): void
    {
        $actions = new \TCB\FlysystemSync\Runner\Bag([
            'update' => null,
            'create' => null,
        ], [
            'update' => null,
            'delete' => null,
        ]);

        $this->assertEquals([], $actions->create_files);
        $this->assertEquals([], $actions->update_files);
        $this->assertEquals([], $actions->delete_files);

        $this->assertEquals([], $actions->create_directories);
        $this->assertEquals([], $actions->update_directories);
        $this->assertEquals([], $actions->delete_directories);
    }
}
