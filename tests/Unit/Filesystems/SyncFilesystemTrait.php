<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems;

use TCB\FlysystemSync\Filesystems\Contracts\SyncFilesystem;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;
use Tests\Support\TestAdapter;
use Tests\Support\UnitTester;

trait SyncFilesystemTrait
{
    protected SyncFilesystem $sync_filesystem;

    protected const DIRECTORIES = [
        'home',
        'home/.ssh',
        'home/thad',
        'home/thad/Apps',
        'home/thad/Documents',
        'home/thad/Music',
        'home/thad/Music/A-B',
        'home/thad/Music/C-F',
    ];

    protected const FILES = [
        'home/user.txt',
        'home/.ssh/config',
        'home/thad/Documents/taxes.md',
        'home/thad/Documents/.passwd',
        'home/thad/Music/README.md',
        'home/thad/Music/A-B/rock.mpg',
        'home/thad/Music/C-F/jazz.mpg',
    ];

    public function _before(): void
    {
        $adapter = new TestAdapter;
        $adapter->addTestingDirectories(...static::DIRECTORIES);

        foreach (static::FILES as $path) {
            $adapter->addTestingFiles($path);
        }

        $this->sync_filesystem = $this->getSyncFilesystem($adapter);
    }

    abstract protected function getSyncFilesystem(TestAdapter $adapter): SyncFilesystem;

    protected function assertFile(UnitTester $I, ?Path $file, string $path): void
    {
        $message = '->assertFile(): ' . $path;

        $I->assertInstanceOf(File::class, $file, $message);

        $I->assertEquals($path, $file->path, $message);
        $I->assertEquals(TestAdapter::DEFAULT_VISIBILITY, $file->visibility, $message);
        $I->assertEquals(TestAdapter::DEFAULT_LAST_MODIFIED, $file->lastModified, $message);
        $I->assertEquals(TestAdapter::DEFAULT_FILESIZE, $file->fileSize, $message);
        $I->assertEquals(TestAdapter::DEFAULT_MIME_TYPE, $file->mimeType, $message);
    }

    protected function assertDirectory(UnitTester $I, ?Path $directory, string $path): void
    {
        $message = '->assertFile(): ' . $path;

        $I->assertInstanceOf(Directory::class, $directory, $message);

        $I->assertEquals($path, $directory->path, $message);
        $I->assertEquals(TestAdapter::DEFAULT_VISIBILITY, $directory->visibility, $message);
        $I->assertEquals(TestAdapter::DEFAULT_LAST_MODIFIED, $directory->lastModified, $message);
    }
}
