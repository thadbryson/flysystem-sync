<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Directories;

use League\Flysystem\FilesystemOperator;

class Delete extends AbstractDirectory
{
    public function execute(FilesystemOperator $filesystem): bool
    {
        $filesystem->deleteDirectory($this->path);

        return $this->isSuccess($filesystem);
    }

    protected function isSuccess(FilesystemOperator $filesystem): bool
    {
        return $filesystem->directoryExists($this->path) === false;
    }
}
