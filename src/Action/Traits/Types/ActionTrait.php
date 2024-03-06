<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\Loader;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

/**
 * @property-read ReaderFilesystem reader
 * @property-read Filesystem       writer
 */
trait ActionTrait
{
    abstract private function readerExists(): bool;

    abstract private function writerExists(): bool;

    public function getDifferences(): array
    {
        $source = Loader::loadPath($reader, $this->path->path);
        $target = Loader::loadPath($writer, $this->path->path);

        return HelperFilesystem::getDifferences($source, $target);
    }

    public function isReaderExistingValid(): bool
    {
        return $this->isOnReader() === $readerExists();
    }

    public function isWriterExistingValid(): bool
    {
        return $this->isOnWriter() === $writerExists();
    }
}
