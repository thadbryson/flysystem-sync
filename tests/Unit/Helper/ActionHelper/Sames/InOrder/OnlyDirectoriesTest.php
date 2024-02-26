<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\ActionHelper\Sames\InOrder;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use TCB\FlysystemSync\Action\ActionHelper;

class OnlyDirectoriesTest extends Unit
{
    protected ActionHelper $actions;

    public function setUp(): void
    {
        $sources = [
            'update'                     => new DirectoryAttributes('update'),
            'update3'                    => new DirectoryAttributes('update3'),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here'),

            'create'           => new DirectoryAttributes('create'),
            'create/where/now' => new DirectoryAttributes('create/where/now'),
            'create-d'         => new DirectoryAttributes('create-d'),
        ];

        $targets = [
            'update'                     => new DirectoryAttributes('update'),
            'update3'                    => new DirectoryAttributes('update3'),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here'),

            'delete/yes/now' => new DirectoryAttributes('delete/yes/now'),
            'deleteT'        => new DirectoryAttributes('deleteT'),
            'delete'         => new DirectoryAttributes('delete'),
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
        $this->assertEquals([], $this->actions->update_directories);
    }

    public function testOnlyFilesGiven(): void
    {
        $this->assertEquals([
            'create'           => new DirectoryAttributes('create'),
            'create/where/now' => new DirectoryAttributes('create/where/now'),
            'create-d'         => new DirectoryAttributes('create-d'),
        ], $this->actions->create_directories);

        $this->assertEquals([
            'delete/yes/now' => new DirectoryAttributes('delete/yes/now'),
            'deleteT'        => new DirectoryAttributes('deleteT'),
            'delete'         => new DirectoryAttributes('delete'),
        ], $this->actions->delete_directories);
    }
}
