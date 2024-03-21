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

    public function file(string $source, string $target = null): Log
    {
        return FileRunner::fromPath($this->reader, $this->writer, $source, $target)->execute();
    }

    public function directoryOnly(string $source, string $target = null): Log
    {
        return DirectoryRunner::fromPath($this->reader, $this->writer, $source, $target)->execute();
    }

    public function directory(string $source, string $target = null): array
    {
        $logs = [];

        $sources = $this->reader->getDirectoryContents($source) ?? throw new DirectoryNotFound($source);
        $targets = $this->writer->getDirectoryContents($target) ?? throw new DirectoryNotFound($target);

        /**
         * @var Path      $source
         * @var Path|null $target
         */
        foreach ($sources as $path => $source) {
            $target = $targets[$path] ?? null;

            $runner = $source->isFile() ?
                new FileRunner($this->reader, $this->writer, $source, $target) :
                new DirectoryRunner($this->reader, $this->writer, $source, $target);

            $logs[$path] = [
                $runner,
                $runner->execute(),
            ];
        }

        // Check for differences after ALL ->execute() have ran.
        /**
         * @var AbstractRunner $runner
         * @var Log            $log
         */
        foreach ($logs as $path => [$runner, $log]) {
            $logs[$path] = $log->add(Log::STAGE_FINAL, $runner->source, $runner->loadTarget());
        }

        return $logs;
    }
}
