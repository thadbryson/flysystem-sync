<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use TCB\FlysystemSync\Exceptions\DirectoryNotFound;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\File;

/**
 * @property-read File $source
 */
class FileRunner extends Runner
{
    protected function assertSource(?Path $source, string $path): File
    {
        if ($source === null) {
            throw new DirectoryNotFound($path);
        }

        if ($source instanceof File) {
            return $source;
        }

        throw new \Exception('');
    }

    protected function create(): void
    {
        $this->update();
    }

    protected function update(): void
    {
        $this->writer->filesystem->writeStream(
            $this->path,
            $this->reader->readStream($this->path)
        );
    }
}
