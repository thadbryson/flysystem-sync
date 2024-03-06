<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Actions\File;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Action\File\DeleteFileAction;
use Tests\Unit\Action\Actions\ActionTestTrait;

use function ltrim;

class DeleteTest extends Unit
{
    use ActionTestTrait;

    public function testAttributes(): void
    {
        $action = new DeleteFileAction($this->reader, $this->writer, $this->file);

        // Interfaces it needs.
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\Action);
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\FileAction);

        $this->assertEquals($this->file, $action->path);
        $this->assertEquals(ltrim(__DIR__, '/'), $action->path->path);

        $this->assertFalse($action->isOnReader());
        $this->assertTrue($action->isOnWriter());
    }
}
