<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

/**
 * @property-read ReaderFilesystem $reader
 * @property-read Filesystem       $writer
 *
 * @property-read string           $path
 */
interface Action
{
    public function execute(): void;

    public function isOnReader(): bool;

    public function isOnWriter(): bool;

    public function type(): string;

    public function isReaderExistingValid(): bool;

    public function isWriterExistingValid(): bool;

    public function getDifferences(): array;
}
