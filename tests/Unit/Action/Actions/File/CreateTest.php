<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Actions\File;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\ReadOnly\ReadOnlyFilesystemAdapter;
use TCB\FlysystemSync\Action\Actions\Directory;

class CreateTest extends Unit
{
    public function testAttributes(): void
    {
        $adapter = new LocalFilesystemAdapter(__DIR__);
        $adapter = new ReadOnlyFilesystemAdapter($adapter);

        $filesystem = new Filesystem($adapter);

        $directory = new Directory\Create($filesystem, new DirectoryAttributes(__FILE__));

        $this->assertTrue($directory instanceof \TCB\FlysystemSync\Action\Actions\Contracts\Directory);
        $this->assertTrue($directory instanceof \TCB\FlysystemSync\Action\Actions\Contracts\Action);
    }
}
