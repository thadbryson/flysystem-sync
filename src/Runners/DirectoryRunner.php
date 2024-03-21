<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use TCB\FlysystemSync\Exceptions\InvalidDirectory;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;

/**
 * @property-read Directory $source
 */
class DirectoryRunner extends AbstractRunner
{
    protected static function assertSource(Path $source): Directory
    {
        if ($source instanceof Directory) {
            return $source;
        }

        throw new InvalidDirectory($source->path);
    }

    protected function create(): void
    {
        $this->writer->filesystem->createDirectory($this->target->path);
    }

    protected function update(): void
    {
        $this->writer->filesystem->setVisibility(
            $this->target->path,
            $this->target->visibility
        );
    }
}
