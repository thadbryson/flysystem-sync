<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Actions\Contracts;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;

interface Action
{
    public function __construct(
        FilesystemOperator|FilesystemReader $reader,
        FilesystemOperator $writer,
        StorageAttributes $path
    );

    public function isFile(): bool;

    public function execute(): void;

    public function isReady(): bool;

    public function isSuccess(): bool;
}
