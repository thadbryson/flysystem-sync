<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

/**
 * @property-read ReaderFilesystem reader
 * @property-read Filesystem       writer
 */
trait ActionTrait
{
    public readonly FileAttributes|DirectoryAttributes $path;

    public readonly string $location;
    
    abstract protected function readerExists(): bool;

    abstract protected function writerExists(): bool;

    public function getDifferences(): array
    {
        $source = HelperFilesystem::loadPath($this->reader, $this->location);
        $target = HelperFilesystem::loadPath($this->writer, $this->location);

        return HelperFilesystem::getDifferences($source, $target);
    }

    public function isReaderExistingValid(): bool
    {
        return $this->isOnReader() === $this->readerExists();
    }

    public function isWriterExistingValid(): bool
    {
        return $this->isOnWriter() === $this->writerExists();
    }
}
