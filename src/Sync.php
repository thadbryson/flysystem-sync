<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Collections\Log;
use TCB\FlysystemSync\Collections\PathCollection;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;
use Throwable;

class Sync
{
    public readonly ReaderFilesystem $reader;

    public readonly PathCollection $paths;

    public array $exceptions = [];

    public function __construct(FilesystemAdapter $reader)
    {
        $this->reader = new ReaderFilesystem($reader);
        $this->paths  = new PathCollection($reader);
    }

    public function file(string $file): static
    {
        try {
            $this->paths->file($file);
        }
        catch (Throwable $exception) {
            $this->addException($file, true, $exception);
        }

        return $this;
    }

    public function directory(string $directory): static
    {
        try {
            $this->paths->directory($directory);
        }
        catch (Throwable $exception) {
            $this->addException($directory, false, $exception);
        }

        return $this;
    }



    public function sync(FilesystemAdapter $writer): Runner\Runner
    {
        return new Runner\Runner($this->reader, $writer, $this->paths);
    }
}
