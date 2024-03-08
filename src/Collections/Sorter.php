<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use TCB\FlysystemSync\Action\Contracts;
use TCB\FlysystemSync\Factory;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

/**
 * Contains Action objects for each type (File, Directory) and
 * Action (create, update, delete, nothing).
 */
readonly class Sorter
{
    /**
     * @var Contracts\CreateDirectory[]
     */
    public array $create_directories;

    /**
     * @var Contracts\CreateFile[]
     */
    public array $create_files;

    /**
     * @var Contracts\DeleteDirectory[]
     */
    public array $delete_directories;

    /**
     * @var Contracts\DeleteFile[]
     */
    public array $delete_files;

    /**
     * @var Contracts\UpdateDirectory[]
     */
    public array $update_directories;

    /**
     * @var Contracts\UpdateFile[]
     */
    public array $update_files;

    /**
     * @var Contracts\NothingDirectory[]
     */
    public array $nothing_directories;

    /**
     * @var Contracts\NothingFile[]
     */
    public array $nothing_files;

    /**
     * @param File[]|Directory[]|null[] $sources
     * @param File[]|Directory[]|null[] $targets
     */
    public function __construct(array $sources, array $targets, Factory $factory)
    {
        $this->initialSort($sources, $targets, $factory);
    }

    protected function initialSort(array $sources, array $targets, Factory $factory): void
    {
        /** @var File|Directory|null $source */
        foreach ($sources as $path => $source) {
            // Build the Action
            $action = $factory->action($source, $targets[$path] ?? null);

            match (true) {
                $action instanceof Contracts\CreateDirectory  => $this->create_directories[$path] = $action,
                $action instanceof Contracts\CreateFile       => $this->create_files[$path] = $action,

                $action instanceof Contracts\DeleteDirectory  => $this->delete_directories[$path] = $action,
                $action instanceof Contracts\DeleteFile       => $this->delete_files[$path] = $action,

                $action instanceof Contracts\UpdateDirectory  => $this->update_directories[$path] = $action,
                $action instanceof Contracts\UpdateFile       => $this->update_files[$path] = $action,

                $action instanceof Contracts\NothingDirectory => $this->nothing_directories[$path] = $action,
                $action instanceof Contracts\NothingFile      => $this->nothing_files[$path] = $action,
            };
        }
    }
}
