<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Directories;

use League\Flysystem\FilesystemOperator;
use TCB\FlysystemSync\Actions\Definition;

abstract class AbstractDirectory
{
    public readonly string $path;

    public readonly ?bool $visibility;

    public function __construct(Definition $action)
    {
        $this->path       = $action->target->path();
        $this->visibility = $action->target->visibility();
    }

    abstract public function execute(FilesystemOperator $filesystem): bool;

    abstract protected function isSuccess(FilesystemOperator $filesystem): bool;
}
