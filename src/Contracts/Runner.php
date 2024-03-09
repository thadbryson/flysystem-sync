<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Contracts;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

interface Runner
{
    public function __construct(Filesystem $reader, Filesystem $writer);

    public function execute(?StorageAttributes $source, ?StorageAttributes $target): bool;

    public function createFile(FileAttributes $source): bool;

    public function deleteFile(FileAttributes $target): bool;

    public function updateFile(FileAttributes $source, StorageAttributes $target): bool;

    public function nothingFile(FileAttributes $source, FileAttributes $target): bool;

    public function createDirectory(DirectoryAttributes $source): bool;

    public function deleteDirectory(DirectoryAttributes $target): bool;

    public function updateDirectory(DirectoryAttributes $source, StorageAttributes $target): bool;

    public function nothingDirectory(DirectoryAttributes $source, DirectoryAttributes $target): bool;
}
