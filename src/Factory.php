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
    public function action(File|Directory|null $source, File|Directory|null $target): Contracts\Action
    {
        $type = ActionEnum::getType($source, $target);

        return match ($type) {
            ActionEnum::CREATE_DIRECTORY  => $this->createDirectory($source),
            ActionEnum::DELETE_DIRECTORY  => $this->deleteDirectory($source),
            ActionEnum::UPDATE_DIRECTORY  => $this->updateDirectory($source),
            ActionEnum::NOTHING_DIRECTORY => $this->nothingDirectory($source),

            ActionEnum::CREATE_FILE       => $this->createFile($source),
            ActionEnum::DELETE_FILE       => $this->deleteFile($source),
            ActionEnum::UPDATE_FILE       => $this->updateFile($source),
            ActionEnum::NOTHING_FILE      => $this->nothingFile($source),
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

    protected function updateDirectory(Directory $source): Contracts\UpdateDirectory
    {
        return new UpdateDirectory($source);
    }

    protected function updateFile(File $source): Contracts\UpdateFile
    {
        return new UpdateFile($source);
    }

    protected function nothingDirectory(Directory $source): Contracts\NothingDirectory
    {
        return new NothingDirectory($source);
    }

    protected function nothingFile(File $source): Contracts\NothingFile
    {
        return new NothingFile($source);
    }
}
