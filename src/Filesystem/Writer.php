<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

/**
 * Extended functions on top of Filesystem
 */
class Writer extends Reader
{
    /**
     * @param File     $source
     * @param resource $contents
     * @param array    $config = []
     */
    public function createFile(File $source, mixed $contents, array $config = []): void
    {
        $this->filesystem->writeStream($source->path, $contents, $config);
        $this->setVisibility($source);
    }

    /**
     * @param Directory $source
     * @param array     $config = []
     */
    public function createDirectory(Directory $source, array $config = []): void
    {
        $this->filesystem->createDirectory($source->path, $config);
        $this->setVisibility($source);
    }

    /**
     * @param File     $source
     * @param resource $contents
     * @param array    $config = []
     */
    public function updateFile(File $source, mixed $contents, array $config = []): void
    {
        $this->filesystem->writeStream($source->path, $contents, $config);
        $this->setVisibility($source);
    }

    public function setVisibility(File|Directory $source): void
    {
        $this->filesystem->setVisibility($source->path, $source->visibility);
    }

    public function delete(File|Directory $target): void
    {
        $target->isFile() ?
            $this->filesystem->delete($target->path) :
            $this->filesystem->deleteDirectory($target->path);
    }
}
