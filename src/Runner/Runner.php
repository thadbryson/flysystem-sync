<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Action\Contracts\Action;
use TCB\FlysystemSync\Collections\PathCollection;
use TCB\FlysystemSync\Filesystem\Loader;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;
use TCB\FlysystemSync\Runner\Results\ActionResult;

/**
 * Runs all the Actions
 */
readonly class Runner
{
    /**
     * @var ResultBuilder[]
     */
    public array $create_files;

    /**
     * @var ResultBuilder[]
     */
    public array $create_directories;

    /**
     * @var ResultBuilder[]
     */
    public array $delete_files;

    /**
     * @var ResultBuilder[]
     */
    public array $delete_directories;

    /**
     * @var ResultBuilder[]
     */
    public array $update_files;

    /**
     * @var ResultBuilder[]
     */
    public array $update_directories;

    /**
     * @var ResultBuilder[]
     */
    public array $nothing_files;

    /**
     * @var ResultBuilder[]
     */
    public array $nothing_directories;

    /**
     */
    public function __construct(
        ReaderFilesystem $reader,
        FilesystemAdapter $writer,
        PathCollection $sources,
    ) {
        $targets = $sources->clone($writer);

        // Hold 8 arrays for create/update/delete/nothing of each type file/directory
        // All actions on those 6 different arrays. Just data for the "nothings".
        $bag = new Bag($sources->found(), $targets->found());
        $bag
            // Get any errors and differences for each PATH.
            ->map(function (Action $action): ResultBuilder {
                return new ResultBuilder($action);
            })

            // Execute all Actions
            ->map(function (ResultBuilder $result) use ($reader, $writer): ResultBuilder {
                return $result->execute($reader, $writer);
            })

            // AFTER ALL the Actions ran, then we get the differences, and other things.
            ->map(function (ResultBuilder $result): ActionResult {
                return $result->finalize();
            });

        $this->create_files       = $bag->create_files;
        $this->create_directories = $bag->create_directories;

        $this->delete_files       = $bag->delete_files;
        $this->delete_directories = $bag->delete_directories;

        $this->update_files       = $bag->update_files;
        $this->update_directories = $bag->update_directories;

        $this->nothing_files       = $bag->nothing_files;
        $this->nothing_directories = $bag->nothing_directories;
    }
}
