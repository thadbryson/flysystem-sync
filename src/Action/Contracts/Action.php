<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

/**
 * @property-read ReaderFilesystem $reader
 * @property-read Filesystem       $writer
 */
interface Action
{
    public function execute(ReaderFilesystem $reader, Filesystem $writer): void;

    public function isOnReader(): bool;

    public function isOnWriter(): bool;

    public function isReaderExistingValid(): bool;

    public function isWriterExistingValid(): bool;

    public function getDifferences(): array;
}
