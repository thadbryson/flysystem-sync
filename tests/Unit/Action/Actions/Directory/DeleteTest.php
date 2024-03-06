<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Actions\Directory;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Action\Directory\DeleteDirectoryAction;
use Tests\Unit\Action\Actions\ActionTestTrait;

use function ltrim;

class DeleteTest extends Unit
{
    use ActionTestTrait;

    public function testAttributes(): void
    {
        $action = new DeleteDirectoryAction($this->reader, $this->writer, $this->directory);

        // Interfaces it needs.
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\Action);
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\DirectoryAction);

        $this->assertEquals($this->directory, $action->path);
        $this->assertEquals(ltrim(__DIR__, '/'), $action->path->path);

        $this->assertFalse($action->isOnReader());
        $this->assertTrue($action->isOnWriter());
    }
}
