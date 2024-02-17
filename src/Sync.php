<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Collections\HydratedCollection;
use TCB\FlysystemSync\Collections\PathCollection;

class Sync
{
    protected readonly FilesystemReadOnly $reader;

    protected readonly PathCollection $paths;

    public function __construct(Filesystem $reader)
    {
        $this->reader = new FilesystemReadOnly($reader);
        $this->paths  = new PathCollection;
    }

    public function all(): array
    {
        return $this->paths->all();
    }

    public function add(string $orig): static
    {
        $orig = $this->reader->assertHas($orig);
        $this->paths->add($orig);

        return $this;
    }

    public function sync(FilesystemOperator $writer): void
    {
        $hydrated = new HydratedCollection($this->reader, $writer, $this->paths);

        /**
         * @var StorageAttributes $orig
         * @var StorageAttributes $dest
         */

        foreach ($hydrated->creates as $orig) {
            $this->put($writer, $orig, $orig->path());
        }

        foreach ($hydrated->deletes as $dest) {
            $this->delete($writer, $dest);
        }

        foreach ($hydrated->updates as [$orig, $dest]) {
            $this->put($writer, $orig, $dest->path());
        }
    }

    protected function delete(FilesystemOperator $writer, StorageAttributes $dest): void
    {
        $dest->isFile() ?
            $writer->delete($dest->path()) :
            $writer->deleteDirectory($dest->path());
    }

    protected function put(FilesystemOperator $writer, StorageAttributes $orig, string $dest_path): void
    {
        $orig->isDir() ?
            $writer->createDirectory($dest_path) :
            $writer->writeStream(
                $dest_path,
                $this->reader->readStream($orig->path())
            );
    }
}
