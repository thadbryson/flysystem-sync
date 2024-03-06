<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\Loader;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;
use Throwable;

use function array_key_exists;
use function array_keys;

class PathCollection
{
    /**
     * @var Log[]
     */
    private array $items = [];

    private readonly ReaderFilesystem $reader;

    public function __construct(FilesystemAdapter $reader)
    {
        $this->reader = new ReaderFilesystem($reader);
    }

    /**
     * Clone with another Filesystem.
     */
    public function clone(FilesystemAdapter $reader): static
    {
        $cloned = new static($reader);

        foreach ($this->items as $current) {
            $current->expecting_file ?
                $cloned->file($current->path) :
                $cloned->directory($current->path);
        }

        return $cloned;
    }

    public function paths(): array
    {
        return array_keys($this->items);
    }

    public function found(): array
    {
        return array_map(fn (Log $log) => $log->found, $this->items);
    }

    public function isCollected(string $path): bool
    {
        return array_key_exists(
            HelperFilesystem::preparePath($path),
            $this->items
        );
    }

    public function file(string $path): void
    {
        $found = Loader::loadPath($this->reader, $path);

        try {
            $this->addInternal($found->path, true, $found, null);
        }
        catch (Throwable $exception) {
            $this->addInternal($found->path, true, null, $exception);
        }
    }

    public function directory(string $path): void
    {
        $found = Loader::loadPath($this->reader, $path);

        try {
            // Need out add outside it's loaded contents below.
            // The directory could be empty and it wouldn't be in the contents.
            $this->addInternal($found->path, false, $found, null);
            $this->directoryContents($found->path);
        }
        catch (Throwable $exception) {
            $this->addInternal($found->path, false, null, $exception);
        }
    }

    protected function directoryContents(string $directory): void
    {
        $contents = Loader::loadPathsDeep($this->reader, [$directory]);

        foreach ($contents as $content) {
            try {
                $this->addInternal($content->path, $content->is_file, $content, null);
            }
            catch (Throwable $exception) {
                $this->addInternal($content->path, $content->is_file, null, $exception);
            }
        }
    }

    private function addInternal(
        string $path,
        bool $is_file,
        File|Directory|null $found,
        Throwable|null $exception
    ): void {
        $this->items[$path] = new Log(
            $path,
            $found,
            $is_file,
            $this->isCollected($path),
            $exception
        );
    }
}
