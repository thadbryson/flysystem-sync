<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\File;

use TCB\FlysystemSync\Actions\Contract;
use TCB\FlysystemSync\Actions\Traits\ActionTrait;
use TCB\FlysystemSync\PathTypes;

class Create implements Contract\Directory, Contract\Create
{
    use ActionTrait;

    public const array ASSERT = [
        'source' => PathTypes::FILE,
        'target' => PathTypes::NON_EXISTING,
    ];

    public function execute(): void
    {
        $this->writer->writeStream(
            $this->target,
            $this->reader->readStream($this->source)
        );
    }
}
