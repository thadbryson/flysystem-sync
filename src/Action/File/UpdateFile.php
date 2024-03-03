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

    public function execute(): void
    {
        $delete = new DeleteFile($this->reader, $this->writer, $this->file);
        $delete->execute();

        $this->writer->writeStream(
            $this->path,
            $this->reader->readStream($this->path)
        );
    }
}
