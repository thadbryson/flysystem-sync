<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\IsSuccessFileTrait;
use TCB\FlysystemSync\Action\Traits\IsSuccessTrait;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class UpdateFile implements Contracts\UpdateFile
{
    use IsSuccessFileTrait;

    public bool $only_visibility;

    public function __construct(
        public File $source,
        public File|Directory $target
    ) {
        $this->only_visibility = $source->isDifferentOnlyVisibility($target);
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        if ($this->only_visibility === false) {
            $writer->writeStream(
                $this->source->path,
                $reader->readStream($this->source->path)
            );
        }

        $writer->setVisibility($this->source->path, $this->source->visibility);
    }
}
