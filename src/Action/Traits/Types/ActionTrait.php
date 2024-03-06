<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;
use TCB\FlysystemSync\Filesystem\Traits\LoaderTrait;

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
        $source = LoaderTrait::loadPath($reader, $this->path->path);
        $target = LoaderTrait::loadPath($writer, $this->path->path);

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
