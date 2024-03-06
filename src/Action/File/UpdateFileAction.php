<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\File;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Contracts\FileAction;
use TCB\FlysystemSync\Action\Traits\Actions\UpdateTrait;
use TCB\FlysystemSync\Action\Traits\Types\FileTrait;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

readonly class UpdateFileAction implements FileAction
{
    use FileTrait,
        UpdateTrait;

    public function execute(ReaderFilesystem $reader, Filesystem $writer): void
    {
        $writer->delete($this->path->path);
        $writer->writeStream(
            $this->path->path,
            $reader->readStream($this->path->path)
        );
    }
}
