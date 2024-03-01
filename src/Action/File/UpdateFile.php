<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\File;

use TCB\FlysystemSync\Action\Contracts\File;
use TCB\FlysystemSync\Action\Traits\Actions\UpdateTrait;
use TCB\FlysystemSync\Action\Traits\Types\FileTrait;

readonly class UpdateFile implements File
{
    use FileTrait,
        UpdateTrait;

    public function execute(): static
    {
        $location = $this->file->path();

        $this->writer->delete($location);
        $this->writer->writeStream(
            $location,
            $this->reader->readStream($location)
        );

        return $this;
    }
}
