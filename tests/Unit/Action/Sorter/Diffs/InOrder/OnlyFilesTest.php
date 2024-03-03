<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Sorter\Diffs\InOrder;

use Codeception\Test\Unit;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;

class OnlyFilesTest extends Unit
{
    protected \TCB\FlysystemSync\Runner\Bag $actions;

    public function setUp(): void
    {
        $sources = [
            'create'               => new FileAttributes('create', 1, 'public'),
            'create1'              => new FileAttributes('create1', 1, 'public'),
            'create/deeper/here/2' => new FileAttributes('create/deeper/here/2', 1, 'public'),

            'update'               => new FileAttributes('update', 109, 'public'),
            'updateA'              => new FileAttributes('updateA', 143, 'public'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1', 221, 'public'),

        ];

        $targets = [
            'update'               => new FileAttributes('update'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1'),
            'updateA'              => new FileAttributes('updateA'),

            'delete1'     => new FileAttributes('delete1', 7, 'priv'),
            'delete/go/1' => new FileAttributes('delete/go/1', 7, 'priv'),
            'delete'      => new FileAttributes('delete', 7, 'priv'),
        ];

        $this->actions = new \TCB\FlysystemSync\Runner\Bag($sources, $targets);
    }

    public function testNoDirectories(): void
    {
        $this->assertEquals([], $this->actions->create_directories);
        $this->assertEquals([], $this->actions->update_directories);
        $this->assertEquals([], $this->actions->delete_directories);
    }

    public function testNoUpdatesAllFilesAreTheSame(): void
    {
        $this->assertEquals([
            'update'               => new FileAttributes('update', 109, 'public'),
            'updateA'              => new FileAttributes('updateA', 143, 'public'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1', 221, 'public'),
        ], $this->actions->update_files);
    }

    public function testOnlyFilesGiven(): void
    {
        $this->assertEquals([
            'create'               => new FileAttributes('create', 1, 'public'),
            'create1'              => new FileAttributes('create1', 1, 'public'),
            'create/deeper/here/2' => new FileAttributes('create/deeper/here/2', 1, 'public'),
        ], $this->actions->create_files);

        $this->assertEquals([
            'delete1'     => new FileAttributes('delete1', 7, 'priv'),
            'delete/go/1' => new FileAttributes('delete/go/1', 7, 'priv'),
            'delete'      => new FileAttributes('delete', 7, 'priv'),
        ], $this->actions->delete_files);
    }
}
