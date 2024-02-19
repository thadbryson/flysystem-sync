<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions;

use League\Flysystem\FilesystemOperator;
use TCB\FlysystemSync\Filesystems\Reader;
use TCB\FlysystemSync\Paths\Contract;
use TCB\FlysystemSync\Paths\Type\NullPath;

readonly class Factory
{
    protected Reader $reader;

    protected FilesystemOperator $writer;

    public function __construct(Reader $reader, FilesystemOperator $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function create(Contract\Path $source, NullPath $target): Directory\Create|File\Create
    {
        return $source->isDirectory() ?
            new Directory\Create($this->reader, $this->writer, $source->path(), $target->path()) :
            new File\Create($this->reader, $this->writer, $source->path(), $target->path());
    }

    public function update(Contract\Path $source, Contract\Path $target): Directory\Update|File\Update
    {
        return $source->isDirectory() ?
            new Directory\Update($this->reader, $this->writer, $source->path(), $target->path()) :
            new File\Update($this->reader, $this->writer, $source->path(), $target->path());
    }

    public function delete(Contract\Path $target): Directory\Delete|File\Delete
    {
        return $target->isDirectory() ?
            new Directory\Delete($this->reader, $this->writer, null, $target->path()) :
            new File\Delete($this->reader, $this->writer, null, $target->path());
    }
}
