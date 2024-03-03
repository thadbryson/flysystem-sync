<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Actions;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

use function ltrim;

trait ActionTestTrait
{
    protected readonly ReaderFilesystem $reader;

    protected readonly Filesystem $writer;

    protected readonly FileAttributes $file;

    protected readonly DirectoryAttributes $directory;

    public function setUp(): void
    {
        $reader = new LocalFilesystemAdapter(__DIR__ . '/../../../../_input/sync-test/source/');
        $writer = new LocalFilesystemAdapter(__DIR__ . '/../../../../_ouput');

        $this->reader = new ReaderFilesystem($reader);
        $this->writer = new Filesystem($writer);

        $this->file      = new FileAttributes(__FILE__);
        $this->directory = new DirectoryAttributes(__DIR__);

        $this->assertEquals(ltrim(__FILE__, '/'), $this->file->path());
        $this->assertEquals(ltrim(__DIR__, '/'), $this->directory->path());
    }
}
