<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

interface File extends Action
{
    public function __construct(ReaderFilesystem $reader, Filesystem $writer, FileAttributes $file);
}
