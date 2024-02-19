<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Collections\Hydrator;
use TCB\FlysystemSync\Filesystems\Collector;

class Sync
{
    public readonly Filesystems\Collector $reader;

    public function __construct(Filesystem $reader)
    {
        $this->reader = new Filesystems\Collector($reader);
    }

    public function sync(Filesystem $writer): void
    {
        $hydrator = new Hydrator(
            $this->reader->all(),
            $this->reader->clone($writer)->all()
        );

        $factory = new Actions\Factory($this->reader->reader, $writer);

        foreach ($hydrator->creates as $target => $source) {
            $factory
                ->create($source, $target)
                ->execute()
            ;
        }

        foreach ($hydrator->updates as [$source, $target]) {
            $factory
                ->update($source, $target)
                ->execute()
            ;
        }

        foreach ($hydrator->deletes as $target) {
            $factory
                ->delete($target)
                ->execute()
            ;
        }
    }
}
