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
        $results  = [];
        $contents = $this->reader->getDirectoryContents($path) ?? throw new DirectoryNotFound($path);

        /** @var Path $source */
        foreach ($contents as $source) {
            $runner = AbstractRunner::factory($this->reader, $this->writer, $source);

            $results[$source->path] = [
                'runner' => $runner,
                'log'    => $runner->execute(),
            ];
        }

        // Check for differences after ALL ->execute() have ran.
        foreach ($results as $path => $current) {
            /**
             * @var Log            $current ['log']
             * @var AbstractRunner $runner
             */
            $runner = $current['runner'];
            $runner->loadTarget();

            $results[$path] = $current['log']->add(
                'final_differences',
                $runner->getDifferences()
            );
        }

        return $contents;
    }
}
