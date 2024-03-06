<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\File;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Contracts\FileAction;
use TCB\FlysystemSync\Action\Traits\Actions\DeleteTrait;
use TCB\FlysystemSync\Action\Traits\Types\FileTrait;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

readonly class DeleteFileAction implements FileAction
{
    use FileTrait,
        DeleteTrait;

    public function execute(ReaderFilesystem $reader, Filesystem $writer): void
    {
        $writer->delete($this->path->path);
    }
}
