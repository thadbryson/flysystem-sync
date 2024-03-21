<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Exceptions\DirectoryNotFound;
use TCB\FlysystemSync\Filesystems\Reader;
use TCB\FlysystemSync\Filesystems\Writer;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Runners\AbstractRunner;
use TCB\FlysystemSync\Runners\DirectoryRunner;
use TCB\FlysystemSync\Runners\FileRunner;

class Sync
{
    protected readonly Reader $reader;

    protected readonly Writer $writer;

    public function __construct(FilesystemAdapter $reader, FilesystemAdapter $writer)
    {
        $this->reader = new Reader($reader);
        $this->writer = new Writer($writer);
    }

    public function file(string $path): Log
    {
        return FileRunner::fromPath($this->reader, $this->writer, $path)->execute();
    }

    public function directoryOnly(string $path): Log
    {
        return DirectoryRunner::fromPath($this->reader, $this->writer, $path)->execute();
    }

    public function directory(string $path): array
    {
        $contents = $this->reader->getDirectoryContents($path) ?? throw new DirectoryNotFound($path);

        /** @var Path $source */
        foreach ($contents as $source) {
            $contents[$source->path] = AbstractRunner::factory($this->reader, $this->writer, $source)->execute();
        }

        // Check for differences after ALL ->execute() have ran.
        /**
         * @var FileRunner|DirectoryRunner $runner
         * @var Log                        $log
         */
        foreach ($contents as $path => [$runner, $log]) {
            $contents[$path] = $log->final($runner->source, $runner->loadTarget());
        }

        return $contents;
    }
}
