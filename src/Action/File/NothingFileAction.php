<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\File;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Contracts\FileAction;
use TCB\FlysystemSync\Action\Traits\Actions\NothingTrait;
use TCB\FlysystemSync\Action\Traits\Types\FileTrait;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

readonly class NothingFileAction implements FileAction
{
    use FileTrait,
        NothingTrait;

    public function execute(ReaderFilesystem $reader, Filesystem $writer): void
    {
    }
}
