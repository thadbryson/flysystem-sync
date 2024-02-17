<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Files;

use League\Flysystem\FilesystemOperator;

class Delete extends AbstractFile
{
    public function execute(FilesystemOperator $filesystem): bool
    {
        $filesystem->deleteDirectory($this->target->path());

        return $this->isSuccess($filesystem);
    }

    protected function isSuccess(FilesystemOperator $filesystem): bool
    {
        return $this->filesystem->directoryExists($this->path);
    }
}
