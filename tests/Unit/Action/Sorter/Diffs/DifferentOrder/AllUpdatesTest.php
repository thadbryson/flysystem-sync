<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Sorter\Diffs\DifferentOrder;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;
use Tests\Unit\Action\Sorter\Diffs\InOrder\AllUpdatesTest as Extended;

class AllUpdatesTest extends Extended
{
    public function setUp(): void
    {
        $sources = [
            'update'               => new FileAttributes('update', 100),
            'update/deeper/home/1' => new DirectoryAttributes('update/deeper/home/1', 'visibility'),
            'update7'              => new FileAttributes('update7', 100),
            'update/deeper/7/now'  => new FileAttributes('update/deeper/7/now', 100),
            'update_00'            => new DirectoryAttributes('update_00', 'visibility'),
            'updateA'              => new FileAttributes('updateA', 100),
            'update-99'            => new DirectoryAttributes('update-99', 'visibility'),
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

        $this->actions = new \TCB\FlysystemSync\Runner\Bag($sources, $targets);
    }
}
