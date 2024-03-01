<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Sorter\Diffs\InOrder;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;

class AllUpdatesTest extends Unit
{
    protected \TCB\FlysystemSync\Runner\Sorter $actions;

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
            'update'               => new FileAttributes('update', 200),
            'updateA'              => new FileAttributes('updateA', 200),
            'update/deeper/home/1' => new DirectoryAttributes('update/deeper/home/1', 'visibility', 100_000_003),
            'update7'              => new FileAttributes('update7', 200),
            'update-99'            => new DirectoryAttributes('update-99', 'visibility'),
            'update_00'            => new DirectoryAttributes('update_00', 'visibility', 4),
            'update/deeper/7/now'  => new FileAttributes('update/deeper/7/now', 100),
            'update/what/here'     => new FileAttributes('update/what/here', 100),
        ];

        $this->actions = new \TCB\FlysystemSync\Runner\Sorter($sources, $targets);
    }

    public function testNoCreatesOrDeletes(): void
    {
        $this->assertEquals([], $this->actions->create_files);
        $this->assertEquals([], $this->actions->delete_files);

        $this->assertEquals([], $this->actions->create_directories);
        $this->assertEquals([], $this->actions->delete_directories);
    }

    public function testUpdateFiles(): void
    {
        $this->assertEquals([
            'update'  => new FileAttributes('update', 100),
            'updateA' => new FileAttributes('updateA', 100),
            'update7' => new FileAttributes('update7', 100),
        ], $this->actions->update_files);
    }

    public function testUpdateDirectories(): void
    {
        $this->assertEquals([
            'update/deeper/home/1' => new DirectoryAttributes('update/deeper/home/1', 'visibility'),
            'update_00'            => new DirectoryAttributes('update_00', 'visibility'),
        ], $this->actions->update_directories);
    }
}
