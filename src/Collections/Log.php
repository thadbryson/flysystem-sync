<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;
use Throwable;

class Log
{
    public readonly string $path;

    public readonly array $errors;

    public readonly Throwable $exception;

    public function __construct(
        string $path,
        public readonly File|Directory|null $found,
        public readonly bool $expecting_file,
        bool $already_collected,
        Throwable $exception = null
    ) {
        $this->path = HelperFilesystem::preparePath($path);

        $this->errors = [
            'type_matches'      => $this->found !== null && $this->found->is_file === $expecting_file,
            'already_collected' => $already_collected,
        ];

        $this->exception = $exception;
    }
}
