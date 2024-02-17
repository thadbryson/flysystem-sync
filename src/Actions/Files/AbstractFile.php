<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Files;

use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemOperator;
use TCB\FlysystemSync\Actions\Definition;

abstract class AbstractFile
{
    public readonly ?FileAttributes $souce;

    public readonly ?FileAttributes $target;

    public function __construct(Definition $action)
    {
        $this->souce  = $action->source;
        $this->target = $action->target;
    }

    abstract public function execute(FilesystemOperator $filesystem): bool;

    abstract protected function isSuccess(FilesystemOperator $filesystem): bool;
}
