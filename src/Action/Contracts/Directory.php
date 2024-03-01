<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

interface Directory extends Action
{
    public function __construct(ReaderFilesystem $reader, Filesystem $writer, DirectoryAttributes $directory);
}
