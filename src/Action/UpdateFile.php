<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\IsSuccessTrait;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class UpdateFile implements Contracts\UpdateFile
{
    use IsSuccessTrait;

    public bool $only_visibility;

    public function __construct(
        public File $source,
        File|Directory $target
    ) {
        $this->only_visibility = $this->source->isDifferentOnlyVisibility($target);
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        if ($this->only_visibility === false) {
            $writer->createFile(
                $this->source,
                $reader->getContents($this->source)
            );
        }

        $writer->setVisibility($this->source);
    }
}
