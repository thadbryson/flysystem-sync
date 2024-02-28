<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Helper\Sames\DifferentOrder;

use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;
use Tests\Unit\Action\Helper\Sames\InOrder\OnlyFilesTest as Extended;

class OnlyFilesTest extends Extended
{
    public function setUp(): void
    {
        $sources = [

            'create/deeper/here/2' => new FileAttributes('create/deeper/here/2'),
            'create1'              => new FileAttributes('create1'),
            'create'               => new FileAttributes('create'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1'),
            'update'               => new FileAttributes('update'),
            'updateA'              => new FileAttributes('updateA'),

        ];

        $targets = [
            'delete'               => new FileAttributes('delete'),
            'update'               => new FileAttributes('update'),
            'delete/go/1'          => new FileAttributes('delete/go/1'),
            'updateA'              => new FileAttributes('updateA'),
            'delete1'              => new FileAttributes('delete1'),
            'update/deeper/home/1' => new FileAttributes('update/deeper/home/1'),
        ];

        $this->actions = new Action\Sorter($sources, $targets);
    }
}
