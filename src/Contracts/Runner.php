<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Contracts;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;

interface Runner
{
    public function __construct(FilesystemAdapter $reader, FilesystemAdapter $writer);

    public function execute(?Path $source, ?Path $target): array;

    public function createFile(File $source): void;

    public function deleteFile(File $target): void;

    public function updateFile(File $source, Path $target): void;

    public function nothingFile(File $source, File $target): void;

    public function createDirectory(Directory $source): void;

    public function deleteDirectory(Directory $target): void;

    public function updateDirectory(Directory $source, Path $target): void;

    public function nothingDirectory(Directory $source, Directory $target): void;
}
