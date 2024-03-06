<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Bag\Diffs\DifferentOrder;

use League\Flysystem\DirectoryAttributes;
use TCB\FlysystemSync\Action;
use Tests\Unit\Action\Bag\Diffs\InOrder\OnlyDirectoriesTest as Extended;

class OnlyDirectoriesTest extends Extended
{
    public function setUp(): void
    {
        $sources = [
            'update'                     => new DirectoryAttributes('update', 'what', 100_000_000),
            'create'                     => new DirectoryAttributes('create', 'what', 100_000_000),
            'create/where/now'           => new DirectoryAttributes('create/where/now', 'what', 100_000_000),
            'create-d'                   => new DirectoryAttributes('create-d', 'what', 100_000_000),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here', 'what', 100_000_000),
            'update3'                    => new DirectoryAttributes('update3', 'what', 100_000_000),
        ];

        $targets = [
            'update'                     => new DirectoryAttributes('update'),
            'update3'                    => new DirectoryAttributes('update3'),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here'),

            'delete/yes/now' => new DirectoryAttributes('delete/yes/now', 'what', 100_000_000),
            'deleteT'        => new DirectoryAttributes('deleteT', 'what', 100_000_000),
            'delete'         => new DirectoryAttributes('delete', 'what', 100_000_000),
        ];

        $this->actions = new \TCB\FlysystemSync\Runner\Bag($sources, $targets);
    }
}
