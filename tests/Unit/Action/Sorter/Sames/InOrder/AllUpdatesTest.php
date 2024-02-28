<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Sorter\Sames\InOrder;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;

class AllUpdatesTest extends Unit
{
    protected Action\Sorter $actions;

    public function setUp(): void
    {
        $sources = [
            'update'               => new FileAttributes('update', 100),
            'updateA'              => new FileAttributes('updateA', 100),
            'update/deeper/home/1' => new DirectoryAttributes('update/deeper/home/1', 'visibility'),
            'update7'              => new FileAttributes('update7', 100),
            'update-99'            => new DirectoryAttributes('update-99', 'visibility'),
            'update_00'            => new DirectoryAttributes('update_00', 'visibility'),
            'update/deeper/7/now'  => new FileAttributes('update/deeper/7/now', 100),
            'update/what/here'     => new FileAttributes('update/what/here', 100),
        ];

        $targets = [
            'update'               => new FileAttributes('update', 100),
            'updateA'              => new FileAttributes('updateA', 100),
            'update/deeper/home/1' => new DirectoryAttributes('update/deeper/home/1', 'visibility'),
            'update7'              => new FileAttributes('update7', 100),
            'update-99'            => new DirectoryAttributes('update-99', 'visibility'),
            'update_00'            => new DirectoryAttributes('update_00', 'visibility'),
            'update/deeper/7/now'  => new FileAttributes('update/deeper/7/now', 100),
            'update/what/here'     => new FileAttributes('update/what/here', 100),
        ];

        $this->actions = new Action\Sorter($sources, $targets);
    }

    public function testAllUpdates(): void
    {
        $this->assertEquals([], $this->actions->create_files);
        $this->assertEquals([], $this->actions->update_files);
        $this->assertEquals([], $this->actions->delete_files);

        $this->assertEquals([], $this->actions->create_directories);
        $this->assertEquals([], $this->actions->update_directories);
        $this->assertEquals([], $this->actions->delete_directories);
    }
}
