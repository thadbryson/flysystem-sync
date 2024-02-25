<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\ActionHelper\Sames\DifferentOrder;

use League\Flysystem\DirectoryAttributes;
use TCB\FlysystemSync\Helper\ActionHelper;
use Tests\Unit\Helper\ActionHelper\Sames\InOrder\OnlyDirectoriesTest as Extended;

class OnlyDirectoriesTest extends Extended
{
    public function setUp(): void
    {
        $sources = [
            'create/where/now'           => new DirectoryAttributes('create/where/now'),
            'update'                     => new DirectoryAttributes('update'),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here'),
            'create'                     => new DirectoryAttributes('create'),
            'update3'                    => new DirectoryAttributes('update3'),
            'create-d'                   => new DirectoryAttributes('create-d'),
        ];

        $targets = [
            'delete'                     => new DirectoryAttributes('delete'),
            'update3'                    => new DirectoryAttributes('update3'),
            'update'                     => new DirectoryAttributes('update'),
            'deleteT'                    => new DirectoryAttributes('deleteT'),
            'update/somewhere/down/here' => new DirectoryAttributes('update/somewhere/down/here'),
            'delete/yes/now'             => new DirectoryAttributes('delete/yes/now'),
        ];

        $this->actions = new ActionHelper($sources, $targets);
    }
}
