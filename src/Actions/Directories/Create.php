<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Directories;

use League\Flysystem\FilesystemOperator;
use TCB\FlysystemSync\FilesystemReadOnly;

class Create extends AbstractDirectory
{
    public array $config = [];

    public function execute(FilesystemOperator $filesystem): bool
    {
        $filesystem->createDirectory($this->path, $this->config);

        if ($this->visibility !== null) {
            $filesystem->setVisibility($this->path, $this->visibility);
        }

        return $this->isSuccess($filesystem);
    }

    protected function isSuccess(FilesystemOperator $filesystem): bool
    {
        return $filesystem->directoryExists($this->path) &&
            (
                $this->visibility === null ||
                $this->visibility === $filesystem->visibility($this->path)
            );
    }
}
