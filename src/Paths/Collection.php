<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use function array_merge;
use function in_array;
use function ltrim;

class Collection
{
    protected array $items = [
        'files'       => [],
        'directories' => [],
    ];

    public function file(string $file): static
    {
        $this->items['files'][] = $this->assert($file);

        return $this;
    }

    /**
     * Throw \Exception when
     *  - path is an empty string
     *  - path is already set in ->files
     *  - path is already set in ->directories
     *
     * @throws \Exception
     */
    protected function assert(string $path): string
    {
        $path = ltrim($path);

        if ($path === '') {
            throw new \Exception;
        }

        if (in_array($path, $this->items['files'], true)) {
            throw new \Exception;
        }

        if (in_array($path, $this->items['directories'], true)) {
            throw new \Exception;
        }

        return $path;
    }

    public function directory(string $directory): static
    {
        // Don't add directory contents here.
        // I want to be able to see what is called in ->file() and ->directory()
        $this->items['directories'][] = $this->assert($directory);

        return $this;
    }

    public function getFiles(): array
    {
        return $this->items['files'];
    }

    public function getDirectories(): array
    {
        return $this->items['directories'];
    }

    public function all(): array
    {
        return array_merge(
            $this->items['files'],
            $this->items['directories']
        );
    }
}
