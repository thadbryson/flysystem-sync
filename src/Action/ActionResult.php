<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Filesystem\FilesystemHelper;

readonly class ActionResult
{
    public bool $is_success;

    public function __construct(
        public FileAttributes|DirectoryAttributes $path,
        public FileAttributes|DirectoryAttributes $result,
        public bool $should_exist,
        public bool $exists
    ) {
        $this->is_success = FilesystemHelper::isSame($path, $result);
    }
}
