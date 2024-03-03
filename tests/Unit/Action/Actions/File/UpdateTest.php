<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Actions\File;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Action\File\UpdateFile;
use Tests\Unit\Action\Actions\ActionTestTrait;

use function ltrim;

class UpdateTest extends Unit
{
    use ActionTestTrait;

    public function testAttributes(): void
    {
        $action = new UpdateFile($this->reader, $this->writer, $this->file);

        // Interfaces it needs.
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\Action);
        $this->assertTrue($action instanceof \TCB\FlysystemSync\Action\Contracts\File);

        $this->assertEquals($this->file, $action->path);
        $this->assertEquals(ltrim(__DIR__, '/'), $action->location);

        $this->assertTrue($action->isOnReader());
        $this->assertTrue($action->isOnWriter());
    }
}
