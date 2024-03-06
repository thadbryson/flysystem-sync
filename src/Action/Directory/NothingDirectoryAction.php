<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Directory;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Contracts\DirectoryAction;
use TCB\FlysystemSync\Action\Traits\Actions\NothingTrait;
use TCB\FlysystemSync\Action\Traits\Types\DirectoryTrait;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

readonly class NothingDirectoryAction implements DirectoryAction
{
    use DirectoryTrait,
        NothingTrait;

    public function execute(ReaderFilesystem $reader, Filesystem $writer): void
    {
    }
}
