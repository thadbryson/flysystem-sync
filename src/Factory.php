<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use TCB\FlysystemSync\Action\Contracts;
use TCB\FlysystemSync\Action\CreateDirectory;
use TCB\FlysystemSync\Action\CreateFile;
use TCB\FlysystemSync\Action\DeleteDirectory;
use TCB\FlysystemSync\Action\DeleteFile;
use TCB\FlysystemSync\Action\Enums\ActionEnum;
use TCB\FlysystemSync\Action\NothingDirectory;
use TCB\FlysystemSync\Action\NothingFile;
use TCB\FlysystemSync\Action\UpdateDirectory;
use TCB\FlysystemSync\Action\UpdateFile;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

class Factory
{
    public final function action(File|Directory|null $source, File|Directory|null $target): Contracts\Action
    {
        $type = ActionEnum::getType($source, $target);

        return match ($type) {
            ActionEnum::CREATE_DIRECTORY  => $this->createDirectory($source),
            ActionEnum::DELETE_DIRECTORY  => $this->deleteDirectory($target),
            ActionEnum::UPDATE_DIRECTORY  => $this->updateDirectory($source, $target),
            ActionEnum::NOTHING_DIRECTORY => $this->nothingDirectory($source, $target),

            ActionEnum::CREATE_FILE       => $this->createFile($source),
            ActionEnum::DELETE_FILE       => $this->deleteFile($target),
            ActionEnum::UPDATE_FILE       => $this->updateFile($source, $target),
            ActionEnum::NOTHING_FILE      => $this->nothingFile($source, $target),
        };
    }

    protected function createDirectory(Directory $source): Contracts\CreateDirectory
    {
        return new CreateDirectory($source);
    }

    protected function createFile(File $source): Contracts\CreateFile
    {
        return new CreateFile($source);
    }

    protected function deleteDirectory(Directory $target): Contracts\DeleteDirectory
    {
        return new DeleteDirectory($target);
    }

    protected function deleteFile(File $target): Contracts\DeleteFile
    {
        return new DeleteFile($target);
    }

    protected function updateDirectory(Directory $source, File|Directory $target): Contracts\UpdateDirectory
    {
        return new UpdateDirectory($source, $target);
    }

    protected function updateFile(File $source, File|Directory $target): Contracts\UpdateFile
    {
        return new UpdateFile($source, $target);
    }

    protected function nothingDirectory(Directory $source, File|Directory $target): Contracts\NothingDirectory
    {
        return new NothingDirectory($source, $target);
    }

    protected function nothingFile(File $source, File|Directory $target): Contracts\NothingFile
    {
        return new NothingFile($source, $target);
    }
}
