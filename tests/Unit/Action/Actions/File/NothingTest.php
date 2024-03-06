<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Actions\File;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Action\File\NothingFileAction;
use Tests\Unit\Action\Actions\ActionTestTrait;

use function ltrim;

class NothingTest extends Unit
{
    use ActionTestTrait;

    public function testAttributes(): void
    {
        $action = new NothingFileAction($this->reader, $this->writer, $this->file);

        // Interfaces it needs.
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\Action);
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\FileAction);

        $this->assertEquals($this->file, $action->path);
        $this->assertEquals(ltrim(__DIR__, '/'), $action->path->path);

        $this->assertTrue($action->isOnReader());
        $this->assertTrue($action->isOnWriter());
    }
}
