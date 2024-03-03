<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Actions\File;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Action\File\DeleteFile;
use Tests\Unit\Action\Actions\ActionTestTrait;

use function ltrim;

class DeleteTest extends Unit
{
    use ActionTestTrait;

    public function testAttributes(): void
    {
        $action = new DeleteFile($this->reader, $this->writer, $this->file);

        // Interfaces it needs.
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\Action);
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\File);

        $this->assertEquals($this->file, $action->path);
        $this->assertEquals(ltrim(__DIR__, '/'), $action->location);

        $this->assertFalse($action->isOnReader());
        $this->assertTrue($action->isOnWriter());
    }
}
