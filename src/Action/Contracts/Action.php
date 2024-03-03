<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

/**
 * @property-read ReaderFilesystem                 $reader
 * @property-read Filesystem                       $writer
 *
 * @property-read FileAttributes|StorageAttributes $path
 * @property-read string                           $location
 */
interface Action
{
    public function execute(): void;

    public function isOnReader(): bool;

    public function isOnWriter(): bool;

    public function isReaderExistingValid(): bool;

    public function isWriterExistingValid(): bool;

    public function getDifferences(): array;
}
