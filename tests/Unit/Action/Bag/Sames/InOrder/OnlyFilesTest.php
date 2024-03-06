<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Bag\Sames\InOrder;

use Codeception\Test\Unit;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;

class OnlyFilesTest extends Unit
{
    protected \TCB\FlysystemSync\Runner\Bag $actions;

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
        $this->assertEquals([], $this->actions->update_files);
    }

    public function testOnlyFilesGiven(): void
    {
        $this->assertEquals([
            'create'               => new FileAttributes('create'),
            'create1'              => new FileAttributes('create1'),
            'create/deeper/here/2' => new FileAttributes('create/deeper/here/2'),
        ], $this->actions->create_files);

        $this->assertEquals([
            'delete'      => new FileAttributes('delete'),
            'delete1'     => new FileAttributes('delete1'),
            'delete/go/1' => new FileAttributes('delete/go/1'),
        ], $this->actions->delete_files);
    }
}
