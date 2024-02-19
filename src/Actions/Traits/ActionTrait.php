<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Traits;

use League\Flysystem\FilesystemOperator;
use TCB\FlysystemSync\Filesystems\Reader;
use TCB\FlysystemSync\Paths\Contract as PathContract;

trait ActionTrait
{
    protected readonly Reader $reader;

    protected readonly FilesystemOperator $writer;

    protected ?string $source;

    protected ?string $target;

    public function __construct(
        Reader $reader,
        FilesystemOperator $writer,
        ?string $source,
        ?string $target
    ) {

        if ($this->isDirectory() !== null) {
            $reader->makeDirectory($source);
            $reader->makeDirectory($target);
        }

        if ($this->isFile() !== null) {
            $reader->makeFile($source);
            $reader->makeFile($target);
        }

        if ($this->isNull() !== null) {

        }

        $this->reader = $reader;
        $this->writer = $writer;

        $this->source = $source;
        $this->target = $target;
    }

    abstract public function execute(): void;

    public function path(): string
    {
        return $this->target;
    }

    public function isFile(): bool
    {
        return $this instanceof PathContract\File;
    }

    public function isDirectory(): bool
    {
        return $this instanceof PathContract\Directory;
    }

    public function isNull(): bool
    {
        return $this instanceof PathContract\NullPath;
    }
}
