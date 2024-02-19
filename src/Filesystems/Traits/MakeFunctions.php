<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems\Traits;

use TCB\FlysystemSync\Paths\Contract\Path;
use TCB\FlysystemSync\Paths\Type;

trait MakeFunctions
{
    public function makePath(string $path): Path
    {
        if ($this->hasPath($path) === false) {
            return new Type\NullPath($path);
        }

        return $this->hasDirectory($path) ?
            new Type\Directory($path) :
            new Type\File($path);
    }

    public function makeFile(string $path): Type\File
    {
        if ($this->hasFile($path) === true) {
            return new Type\File($path);
        }

        throw new \Exception;
    }

    public function makeDirectory(string $path): Type\Directory
    {
        if ($this->hasDirectory($path) === true) {
            return new Type\Directory($path);
        }

        throw new \Exception;
    }
}
