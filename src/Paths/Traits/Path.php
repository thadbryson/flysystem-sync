<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Traits;

use TCB\FlysystemSync\Paths\Contracts\Path as PathContract;

use function ltrim;

trait Path
{
    public readonly string $path;

    public readonly ?string $visibility;

    public readonly ?int $last_modified;

    public readonly bool $is_file;

    public readonly bool $is_directory;

    /**
     * @throws \Exception - Must be the same PATH
     */
    public function isEqual(PathContract $compare): bool
    {
        return
            $this->path === $compare->path &&
            $this->last_modified === $compare->last_modified &&
            $this->visibility === $compare->visibility &&
            $this->is_file === $compare->is_file &&
            $this->is_directory === $compare->is_directory;
    }

    protected function constructSetup(
        string $path,
        ?string $visibility,
        ?int $last_modified,
        ?bool $is_file
    ): void {
        $this->path = ltrim($path, '/');

        $this->visibility    = $visibility;
        $this->last_modified = $last_modified;

        $this->is_file      = $is_file;
        $this->is_directory = !$is_file;
    }
}
