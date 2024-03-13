<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use TCB\FlysystemSync\Exceptions\DirectoryNotFound;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;

/**
 * @property-read Directory $source
 */
class DirectoryRunner extends Runner
{
    protected function assertSource(?Path $source, string $path): Directory
    {
        if ($source === null) {
            throw new DirectoryNotFound($path);
        }

        if ($source instanceof Directory) {
            return $source;
        }

        throw new \Exception('');
    }

    protected function create(): void
    {
        $this->writer->filesystem->createDirectory($this->path);
    }

    protected function update(): void
    {
        $this->writer->filesystem->setVisibility(
            $this->path,
            $this->source->visibility
        );
    }
}
