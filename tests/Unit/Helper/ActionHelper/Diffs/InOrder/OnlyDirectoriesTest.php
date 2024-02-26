<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\ActionHelper\Diffs\InOrder;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use TCB\FlysystemSync\Action\ActionHelper;

class OnlyDirectoriesTest extends Unit
{
    protected ActionHelper $actions;

    public function setUp(): void
    {
        $sources = [
            'update'                     => new DirectoryAttributes('update', 'what', 100_000_000),
            'update3'                    => new DirectoryAttributes('update3', 'what', 100_000_000),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here', 'what', 100_000_000),

            'create'           => new DirectoryAttributes('create', 'what', 100_000_000),
            'create/where/now' => new DirectoryAttributes('create/where/now', 'what', 100_000_000),
            'create-d'         => new DirectoryAttributes('create-d', 'what', 100_000_000),
        ];

        $targets = [
            'update'                     => new DirectoryAttributes('update'),
            'update3'                    => new DirectoryAttributes('update3'),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here'),

            'delete/yes/now' => new DirectoryAttributes('delete/yes/now', 'what', 100_000_000),
            'deleteT'        => new DirectoryAttributes('deleteT', 'what', 100_000_000),
            'delete'         => new DirectoryAttributes('delete', 'what', 100_000_000),
        ];

        $this->actions = new ActionHelper($sources, $targets);
    }

    public function testNoFiles(): void
    {
        $this->assertEquals([], $this->actions->create_files);
        $this->assertEquals([], $this->actions->update_files);
        $this->assertEquals([], $this->actions->delete_files);
    }

    public function testNoUpdatesAllDirectoriesAreTheSame(): void
    {
        $this->assertEquals([
            'update'                     => new DirectoryAttributes('update', 'what', 100_000_000),
            'update3'                    => new DirectoryAttributes('update3', 'what', 100_000_000),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here', 'what', 100_000_000),
        ], $this->actions->update_directories);
    }

    public function testOnlyFilesGiven(): void
    {
        $this->assertEquals([
            'create'           => new DirectoryAttributes('create', 'what', 100_000_000),
            'create/where/now' => new DirectoryAttributes('create/where/now', 'what', 100_000_000),
            'create-d'         => new DirectoryAttributes('create-d', 'what', 100_000_000),
        ], $this->actions->create_directories);

        $this->assertEquals([
            'delete/yes/now' => new DirectoryAttributes('delete/yes/now', 'what', 100_000_000),
            'deleteT'        => new DirectoryAttributes('deleteT', 'what', 100_000_000),
            'delete'         => new DirectoryAttributes('delete', 'what', 100_000_000),
        ], $this->actions->delete_directories);
    }
}
