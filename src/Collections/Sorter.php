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
     * @var Contracts\Create[]
     */
    public array $create_files;

    /**
     * @var Contracts\Create[]
     */
    public array $create_directories;

    /**
     * @var Contracts\Delete[]
     */
    public array $delete_files;

    /**
     * @var Contracts\Delete[]
     */
    public array $delete_directories;

    /**
     * @var Contracts\Update[]
     */
    public array $update_files;

    /**
     * @var Contracts\Update[]
     */
    public array $update_directories;

    /**
     * @var Contracts\Nothing[]
     */
    public array $nothing_files;

    /**
     * @var Contracts\Nothing[]
     */
    public array $nothing_directories;

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
            $target = $this->assert($path, $source, $targets);

            $action = $factory->action($source, $target);

            match (true) {
                $action->isCreate() && $action->isFile()       => $this->create_files[$path] = $action,
                $action->isCreate() && $action->isDirectory()  => $this->create_directories[$path] = $action,

                $action->isDelete() && $action->isFile()       => $this->delete_files[$path] = $action,
                $action->isDelete() && $action->isDirectory()  => $this->delete_directories[$path] = $action,

                $action->isUpdate() && $action->isFile()       => $this->update_files[$path] = $action,
                $action->isUpdate() && $action->isDirectory()  => $this->update_directories[$path] = $action,

                $action->isNothing() && $action->isFile()      => $this->nothing_files[$path] = $action,
                $action->isNothing() && $action->isDirectory() => $this->nothing_directories[$path] = $action,
            };
        }
    }

    protected function assert(string $path, mixed $source, array $targets): File|Directory|null
    {
        $target = $targets[$path] ?? null;

        // Both can't be NULL
        if ($source === null && $target === null) {
            throw new \InvalidArgumentException('');
        }

        if ($source !== null &&
            $source instanceof File === false &&
            $source instanceof Directory === false
        ) {
            throw new \InvalidArgumentException('Bad SOURCE');
        }

        if ($target !== null &&
            $target instanceof File === false &&
            $target instanceof Directory === false
        ) {
            throw new \InvalidArgumentException('Bad TARGET');
        }

        return $target;
    }
}
