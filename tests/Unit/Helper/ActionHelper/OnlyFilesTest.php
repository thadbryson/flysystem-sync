<?php

declare(strict_types = 1);

namespace Unit\Helper\ActionHelper;

use Codeception\Test\Unit;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Helper\ActionHelper;

class OnlyFilesTest extends Unit
{
    protected readonly ActionHelper $actions;

    public function setUp(): void
    {
        $sources = [
            'create'               => new FileAttributes('create'),
            'create1'              => new FileAttributes('create1'),
            'create/deeper/here/2' => new FileAttributes('create/deeper/here/2'),

            'update'               => new FileAttributes('update'),
            'updateA'              => new FileAttributes('updateA'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1'),

        ];

        $targets = [
            'update'               => new FileAttributes('update'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1'),
            'updateA'              => new FileAttributes('updateA'),

            'delete1'     => new FileAttributes('delete1'),
            'delete/go/1' => new FileAttributes('delete/go/1'),
            'delete'      => new FileAttributes('delete'),
        ];

        $this->actions = new ActionHelper($sources, $targets);
    }

    public function testOnlyFilesGiven(): void
    {
        $this->assertEquals([
            'create'               => new FileAttributes('create'),
            'create1'              => new FileAttributes('create1'),
            'create/deeper/here/2' => new FileAttributes('create/deeper/here/2'),
        ], $this->actions->create_files);

        $this->assertEquals([
            'update'               => new FileAttributes('update'),
            'updateA'              => new FileAttributes('updateA'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1'),
        ], $this->actions->update_files);

        $this->assertEquals([
            'delete'      => new FileAttributes('delete'),
            'delete1'     => new FileAttributes('delete1'),
            'delete/go/1' => new FileAttributes('delete/go/1'),
        ], $this->actions->delete_files);

        // No Directories
        $this->assertEquals([], $this->actions->create_directories);
        $this->assertEquals([], $this->actions->update_directories);
        $this->assertEquals([], $this->actions->delete_directories);
    }
}
