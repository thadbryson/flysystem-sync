<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Directory;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Contracts\DirectoryAction;
use TCB\FlysystemSync\Action\Traits\Actions\CreateTrait;
use TCB\FlysystemSync\Action\Traits\Types\DirectoryTrait;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

readonly class CreateDirectoryAction implements DirectoryAction
{
    use DirectoryTrait,
        CreateTrait;

    public function execute(ReaderFilesystem $reader, Filesystem $writer): void
    {
        $writer->createDirectory($this->path->path);
    }
}
