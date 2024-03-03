<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Action\Contracts\Action;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;

/**
 * Runs all the Actions
 */
class Runner
{
    /**
     * @var ResultBuilder[]
     */
    public readonly array $create_files;

    /**
     * @var ResultBuilder[]
     */
    public readonly array $create_directories;

    /**
     * @var ResultBuilder[]
     */
    public readonly array $delete_files;

    /**
     * @var ResultBuilder[]
     */
    public readonly array $delete_directories;

    /**
     * @var ResultBuilder[]
     */
    public readonly array $update_files;

    /**
     * @var ResultBuilder[]
     */
    public readonly array $update_directories;

    /**
     * @var ResultBuilder[]
     */
    public readonly array $nothing_files;

    /**
     * @var ResultBuilder[]
     */
    public readonly array $nothing_directories;

    public function __construct(
        Filesystem|FilesystemAdapter $reader,
        Filesystem|FilesystemAdapter $writer,
        array $paths,
    ) {
        $sources = HelperFilesystem::loadPathsMany($reader, $paths);    // Load all set paths
        $targets = HelperFilesystem::loadPathsMany($writer, $sources);  // Find matching targets

        // Hold 8 arrays for create/update/delete/nothing of each type file/directory
        // All actions on those 6 different arrays. Just data for the "nothings".
        $bag = new Bag($reader, $writer, $sources, $targets);
        $bag
            // Get any errors and differences for each PATH.
            ->map(function (Action $action): ResultBuilder {
                return new ResultBuilder($action);
            })

            // Execute all Actions
            ->map(function (ResultBuilder $result): ResultBuilder {
                return $result->execute();
            })

            // AFTER ALL the Actions ran, then we get the differences, and other things.
            ->map(function (ResultBuilder $result): Result {
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
