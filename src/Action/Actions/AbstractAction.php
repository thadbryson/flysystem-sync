<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Actions;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Action\Actions\Contracts\Action;
use TCB\FlysystemSync\Filesystem\Reader;

abstract readonly class AbstractAction implements Action
{
    protected Reader $reader;

    protected FilesystemOperator $writer;

    protected StorageAttributes $path;

    public function __construct(
        FilesystemOperator|FilesystemReader $reader,
        FilesystemOperator $writer,
        StorageAttributes $path
    ) {
        $this->reader = new Reader($reader);
        $this->writer = $writer;

        $this->path = $path;

        if ($this->isFile() !== $this->path->isFile() ||
            !$this->isFile() !== $this->path->isDir()) {
            throw new \Exception('File/Directory types do not match.');
        }
    }

    abstract public function execute(): void;

    abstract protected function isExistingReader(): bool;

    abstract protected function isExistingWriterBefore(): bool;

    protected function existsReader(): bool
    {
        // Handle different file/dir types on reader/writer?
        return $this->isFile() ?
            $this->reader->fileExists($this->path->path()) :
            $this->reader->directoryExists($this->path->path());
    }

    protected function existsWriter(): bool
    {
        return $this->isFile() ?
            $this->writer->fileExists($this->path->path()) :
            $this->writer->directoryExists($this->path->path());
    }

    public function isReady(): bool
    {
        return $this->existsReader() === $this->isExistingReader() &&
               $this->existsWriter() === $this->isExistingWriterBefore();
    }

    public function isSuccess(): bool
    {
        return $this->existsReader() === $this->isExistingReader() &&
               $this->existsWriter() === $this->isExistingReader();     // Should match "reader" / source
    }
}
