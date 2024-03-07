<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Action\Contracts\Action;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class UpdateDirectory implements Action
{
    public array $differences;

    public function __construct(
        public Directory $source,
        public File|Directory $target
    ) {
        $this->differences = $this->source->getDifferences($this->target);
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        foreach ($this->differences as $property => $value) {
            match ($property) {
                StorageAttributes::ATTRIBUTE_VISIBILITY => $writer->setVisibility($this->target->path, $value),

                default                                 => null
            };
        }
    }
}
