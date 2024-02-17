<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\FilesystemReadOnly;

class Definition
{
    public readonly FileAttributes|DirectoryAttributes|null $source;

    public readonly FileAttributes|DirectoryAttributes|null $target;

    public readonly bool $is_file;

    public readonly bool $is_directory;

    public readonly bool $has_source;

    public readonly bool $has_target;

    public readonly bool $has_action;

    public readonly bool $is_created;

    public readonly bool $is_deleted;

    public readonly bool $is_updated;

    protected ?bool $is_success = null;

    public function __construct(
        FileAttributes|DirectoryAttributes|null $source,
        FileAttributes|DirectoryAttributes|null $target,
    ) {
        $this->source = $source;
        $this->target = $target;

        $this->is_file      = $target instanceof FileAttributes;
        $this->is_directory = $target instanceof DirectoryAttributes;

        $this->has_source = $source !== null;
        $this->has_target = $target !== null;

        $this->is_created = $this->has_source && !$this->has_target;
        $this->is_deleted = !$this->has_source && $this->has_target;
        $this->is_updated = $this->has_source && $this->has_target && FilesystemReadOnly::isSame($source, $target) === false;

        $this->has_action = $this->is_created || $this->is_deleted || $this->is_updated;
    }

    public function setSuccess(?bool $success): void
    {
        $this->is_success = $success;
    }

    public function hasRan(): bool
    {
        return $this->is_success !== null;
    }

    public function isSuccess(): bool
    {
        return $this->is_success === true;
    }
}
