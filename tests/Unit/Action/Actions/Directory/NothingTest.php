<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Actions\Directory;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Action\Directory\NothingDirectoryAction;
use Tests\Unit\Action\Actions\ActionTestTrait;

use function ltrim;

class NothingTest extends Unit
{
    use ActionTestTrait;

    public function testAttributes(): void
    {
        $action = new NothingDirectoryAction($this->reader, $this->writer, $this->directory);

        // Interfaces it needs.
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\Action);
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\DirectoryAction);

        $this->assertEquals($this->directory, $action->path);
        $this->assertEquals(ltrim(__DIR__, '/'), $action->path->path);

        $this->assertTrue($action->isOnReader());
        $this->assertTrue($action->isOnWriter());
    }
}