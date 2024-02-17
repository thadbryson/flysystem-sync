<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Files;

use League\Flysystem\FilesystemOperator;

class Create extends AbstractFile
{
    public array $config = [];

    public function execute(FilesystemOperator $filesystem): bool
    {
        $contents = $filesystem_source->readStream($this->source->path());

        $filesystem->writeStream($this->target->path(), $contents, $this->config);

        // Save memory
        unset($contents);

        $this->executeVisibility();
    }

    protected function isSuccess(FilesystemOperator $filesystem): bool
    {
        return $filesystem->directoryExists($this->path) &&
            (
                $this->visibility === null ||
                $this->visibility === $filesystem->visibility($this->path)
            );
    }

    protected function executeVisibility(string $path): void
    {
        $visibility = $this->target->visibility();

        if ($visibility !== null) {
            $filesystem->setVisibility($path, $visibility);
        }
    }
}
