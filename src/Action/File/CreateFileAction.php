<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\File;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Contracts\FileAction;
use TCB\FlysystemSync\Action\Traits\Actions\CreateTrait;
use TCB\FlysystemSync\Action\Traits\Types\FileTrait;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

readonly class CreateFileAction implements FileAction
{
    use FileTrait,
        CreateTrait;

    public function execute(ReaderFilesystem $reader, Filesystem $writer): void
    {
        $writer->writeStream(
            $this->path->path,
            $reader->readStream($this->path->path)
        );
    }
}
