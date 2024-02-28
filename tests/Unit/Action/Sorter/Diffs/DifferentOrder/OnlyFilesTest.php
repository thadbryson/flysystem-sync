<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Sorter\Diffs\DifferentOrder;

use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;
use Tests\Unit\Action\Sorter\Diffs\InOrder\OnlyFilesTest as Extended;

class OnlyFilesTest extends Extended
{
    public function setUp(): void
    {
        $sources = [
            'create1'              => new FileAttributes('create1', 1, 'public'),
            'create/deeper/here/2' => new FileAttributes('create/deeper/here/2', 1, 'public'),
            'updateA'              => new FileAttributes('updateA', 143, 'public'),
            'create'               => new FileAttributes('create', 1, 'public'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1', 221, 'public'),
            'update'               => new FileAttributes('update', 109, 'public'),
        ];

        $targets = [
            'update'               => new FileAttributes('update'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1'),
            'updateA'              => new FileAttributes('updateA'),

            'delete1'     => new FileAttributes('delete1', 7, 'priv'),
            'delete/go/1' => new FileAttributes('delete/go/1', 7, 'priv'),
            'delete'      => new FileAttributes('delete', 7, 'priv'),
        ];

        $this->actions = new Action\Sorter($sources, $targets);
    }
}
