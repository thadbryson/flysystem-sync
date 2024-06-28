<?php

declare(strict_types=1);

namespace TCB\FlysystemSync;

use Illuminate\Support\Collection;
use League\Flysystem\Filesystem;

class Sync
{
    /**
     * Util object for getting WRITE, UPDATE, and DELETE paths.
     */
    protected readonly ContentBag $bag;

    public function __construct(
        public readonly Filesystem $source,
        public readonly Filesystem $target,
        public readonly array $config = [],
        string $directory = '/')
    {
        $this->bag = new ContentBag($source, $target, $directory);
    }

    /**
     * Sync any creates.
     */
    public function syncCreates(): static
    {
        return $this->put($this->bag->creates);
    }

    /**
     * Sync any updates.
     */
    public function syncUpdates(): static
    {
        return $this->put($this->bag->updates);
    }

    /**
     * Sync any deletes.
     */
    public function syncDeletes(): static
    {
        foreach ($this->bag->deletes as $path) {

            // A directory delete may of deleted this path already.
            if ($path->isFile()) {
                $this->target->delete($path->path());
            }
            // A directory? They're deleted a special way.
            elseif ($path->isDir()) {
                $this->target->deleteDirectory($path->path());
            }
        }

        return $this;
    }

    /**
     * Call $this->syncCreates(), $this->syncUpdates(), and $this->syncDeletes()
     *
     * @return $this
     */
    public function sync(): static
    {
        return $this
            ->syncCreates()
            ->syncUpdates()
            ->syncDeletes();
    }

    /**
     * Call ->put() on $slave. Update/Create content from $target. Also sets visibility on slave.
     */
    protected function put(Collection $collection): static
    {
        $collection->each(function (Action $action): void {

            // Otherwise create or update the file.
            if ($action->source->isFile()) {
                $contents = $this->source->readStream($action->path);

                $this->target->createStream($action->path, $contents, $this->config);
            }
            // A directory? Create it.
            elseif ($action->source->isDir()) {
                $this->target->createDirectory($action->path, $this->config);
            }
            else {
                throw new \RuntimeException('StorageAttributes must be either a "file" or "directory"');
            }
        });

        return $this;
    }
}
