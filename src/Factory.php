<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use TCB\FlysystemSync\Action\Contracts\Action;
use TCB\FlysystemSync\Action\CreateDirectory;
use TCB\FlysystemSync\Action\CreateFile;
use TCB\FlysystemSync\Action\DeleteDirectory;
use TCB\FlysystemSync\Action\DeleteFile;
use TCB\FlysystemSync\Action\NothingDirectory;
use TCB\FlysystemSync\Action\NothingFile;
use TCB\FlysystemSync\Action\UpdateDirectory;
use TCB\FlysystemSync\Action\UpdateFile;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

class Factory
{
    public function action(File|Directory|null $source, File|Directory|null $target): Action
    {
        // Both can't be NULL.
        // Should be impossible.
        if ($source === null && $target === null) {
            throw new \InvalidArgumentException('');
        }

        // only SOURCE -> create TARGET
        if ($source !== null && $target === null) {
            return $source->isFile() ?
                $this->createFile($source) :
                $this->createDirectory($source);
        }
        // no SOURCE -> delete TARGET
        elseif ($source === null && $target !== null) {
            return $target->isFile() ?
                $this->deleteFile($target) :
                $this->deleteDirectory($target);
        }
        // Has SOURCE and TARGET but different
        elseif ($source->isDifferent($target)) {
            return $source->isFile() ?
                $this->updateFile($source, $target) :
                $this->updateDirectory($source, $target);
        }

        // Same properties
        return $source->isFile() ?
            $this->nothingFile($source, $target) :
            $this->nothingDirectory($source, $target);
    }

    protected function createFile(File $target): Action
    {
        return new CreateFile($target);
    }

    protected function deleteFile(File $target): Action
    {
        return new DeleteFile($target);
    }

    protected function updateFile(File $source, File|Directory $target): Action
    {
        return new UpdateFile($source, $target);
    }

    protected function nothingFile(File $source, File|Directory $target): Action
    {
        return new NothingFile($source, $target);
    }

    protected function createDirectory(Directory $target): Action
    {
        return new CreateDirectory($target);
    }

    protected function deleteDirectory(Directory $target): Action
    {
        return new DeleteDirectory($target);
    }

    protected function updateDirectory(Directory $source, File|Directory $target): Action
    {
        return new UpdateDirectory($source, $target);
    }

    protected function nothingDirectory(Directory $source, File|Directory $target): Action
    {
        return new NothingDirectory($source, $target);
    }
}
