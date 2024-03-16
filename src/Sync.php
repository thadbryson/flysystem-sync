<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Exceptions\DirectoryNotFound;
use TCB\FlysystemSync\Filesystems\Reader;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Runners\AbstractRunner;
use TCB\FlysystemSync\Runners\DirectoryRunner;
use TCB\FlysystemSync\Runners\FileRunner;

class Sync
{
    public function __construct(
        protected readonly FilesystemAdapter $reader,
        protected readonly FilesystemAdapter $writer
    ) {
    }

    public function file(string $path): bool
    {
        return FileRunner::fromPath($this->reader, $this->writer, $path)
            ->execute();
    }

    public function directory(string $path): array
    {
        $contents = (new Reader($this->reader))->getDirectoryContents($path) ?? throw new DirectoryNotFound($path);

        /** @var Path $source */
        foreach ($contents as $path => $source) {
            $runner = AbstractRunner::factory($this->reader, $this->writer, $source);

            $contents[$path] = [
                'path'    => $path,
                'runner'  => $runner,
                'source'  => $source->toArray(),
                'execute' => $runner->execute(),
            ];
        }

        // Check for differences after ALL ->execute() have ran.
        foreach ($contents as $path => $result) {
            $contents[$path]['final'] = $result['runner']->isSame();
        }

        return $contents;
    }

    public function directoryOnly(string $path): bool
    {
        return DirectoryRunner::fromPath($this->reader, $this->writer, $path)
            ->execute();
    }
}
