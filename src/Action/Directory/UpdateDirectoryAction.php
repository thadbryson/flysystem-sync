<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Directory;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Contracts\DirectoryAction;
use TCB\FlysystemSync\Action\Traits\Actions\UpdateTrait;
use TCB\FlysystemSync\Action\Traits\Types\DirectoryTrait;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

readonly class UpdateDirectoryAction implements DirectoryAction
{
    use DirectoryTrait,
        UpdateTrait;

    public function execute(ReaderFilesystem $reader, Filesystem $writer): void
    {
        $writer->deleteDirectory($this->path->path);
        $writer->createDirectory($this->path->path);
    }
}
