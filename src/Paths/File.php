<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Paths\Contracts\Path as PathContract;
use TCB\FlysystemSync\Paths\Traits\Path;

readonly class File implements PathContract
{
    use Path;

    public ?int $file_size;

    public ?string $mime_type;

    public function __construct(
        string $path,
        ?string $visibility,
        ?int $last_modified,
        ?int $file_size,
        ?string $mime_type
    ) {
        $this->constructSetup($path, $visibility, $last_modified, true);

        $this->file_size = $file_size;
        $this->mime_type = $mime_type;
    }
}
