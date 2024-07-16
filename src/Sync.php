<?php

declare(strict_types=1);

namespace TCB\FlysystemSync;

use Illuminate\Support\Collection;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Action;
use TCB\FlysystemSync\Directory\Actions;

/**
 * @todo Add config [] for createDirectory() and writeStream()
 */
class Sync
{
    /**
     * Util object for getting WRITE, UPDATE, and DELETE paths.
     */
    public readonly Actions $bag;

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function __construct(
        public readonly Filesystem $sources,
        public readonly Filesystem $targets,
        string $directory = '/'
    )
    {
        $this->bag = new Actions($sources, $targets, $directory);
    }

    /**
     * Sync any creates.
     */
    public function syncCreates(): static
    {
//        return $this->put($this->bag->creates_directories);
    }

    /**
     * Sync any deletes.
     */
    public function syncDeletes(): static
    {
        /** @var Action $action */
//        foreach ($this->bag->deletes as $action) {
//
//            $action->is_file ?
//                $this->targets->delete($action->path) :
//                $this->targets->deleteDirectory($action->path);
//        }

        return $this;
    }

    /**
     * Sync any updates.
     */
    public function syncUpdates(): static
    {
//        return $this->put($this->bag->updates);
    }

    /**
     * Call $this->syncCreates(), $this->syncUpdates(), and $this->syncDeletes()
     */
    public function sync(): static
    {
        return $this
            ->syncDeletes()
            ->syncCreates()
            ->syncUpdates();
    }

    /**
     * CREATE or UPDATE a FILE or DIRECTORY
     */
    protected function put(Collection $collection): static
    {
        /** @var Action $action */
        foreach ($collection as $action) {

            // FILE
            $action->is_file ?

                $this->targets->writeStream(
                    $action->path,
                    // Maybe save memory by not assigning to another var?
                    $this->sources->readStream($action->path)
                ) :

                // DIRECTORY
                $this->targets->createDirectory($action->path);
        }

        return $this;
    }
}
