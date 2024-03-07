<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class UpdateDirectory implements Contracts\UpdateDirectory
{
    public bool $needs_creation;

    public function __construct(
        public Directory $source,
        File|Directory $target
    ) {
        $this->needs_creation = $this->source->isDifferentProperties($target);
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        if ($this->needs_creation) {
            $writer->createDirectory($this->source->path);
        }

        $writer->setVisibility($this->source->path, $this->source->visibility);
    }
}
