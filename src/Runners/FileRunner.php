<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use TCB\FlysystemSync\Exceptions\InvalidFile;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\File;

/**
 * @property-read File $source
 */
class FileRunner extends AbstractRunner
{
    public static function assertSource(?Path $source, string $path): File
    {
        if ($source instanceof File) {
            return $source;
        }

        throw new InvalidFile($path);
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
