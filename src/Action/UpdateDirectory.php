<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\IsSuccessDirectoryTrait;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class UpdateDirectory implements Contracts\UpdateDirectory
{
    use IsSuccessDirectoryTrait;

    public bool $only_visibility;

    public function __construct(
        public Directory $source,
        public File|Directory $target
    ) {
        $this->only_visibility = $source->isDifferentOnlyVisibility($target);
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        // It could be a file.
        if ($this->only_visibility === false) {
            $writer->createDirectory($this->source->path);
        }

        // Only thing to update on a directory is its visibility.
        $writer->setVisibility($this->source->path, $this->source->visibility);
    }
}
