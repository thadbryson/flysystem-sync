<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;

use function TCB\FlysystemSync\Functions\Helper\path_prepare;

abstract class AbstractPath
{
    public readonly string $path;

    public readonly string $type;

    public readonly ?bool $exists;

    public readonly ?string $visibility;

    public readonly ?int $lastModified;

    public readonly bool $is_file;

    public readonly bool $is_directory;

    public function __construct(
        string $path,
        ?bool $exists,
        ?string $visibility,
        ?int $lastModified,
        bool $is_file,
        bool $is_directory
    ) {
        $this->path   = path_prepare($path);
        $this->exists = $exists;

        $this->visibility   = $visibility;
        $this->lastModified = $lastModified;

        $this->is_file      = $is_file;
        $this->is_directory = $is_directory;

        $this->type = $this->is_file ?
            'file' :
            'directory';
    }

    abstract public function toArray(): array;

    public static function fromAttributes(StorageAttributes $attributes, ?bool $exists = null): File|Directory
    {
        return match (true) {
            $attributes instanceof FileAttributes      => File::fromAttributes($attributes, $exists),
            $attributes instanceof DirectoryAttributes => Directory::fromAttributes($attributes, $exists),
        };
    }

    public function toString(): string
    {
        return $this->path;
    }
}
