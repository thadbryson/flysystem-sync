<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\Filesystem\IsSame\Files;

use Codeception\Test\Unit;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Helper\FilesystemHelper;

use function time;

use const PHP_INT_MAX;

class DiffTest extends Unit
{
    protected function checkDifferent(
        string $path,
        ?int $filesize,
        ?string $visibility,
        ?int $last_modified,
        ?string $mime_type
    ): void {
        $given = new FileAttributes($path, $filesize, $visibility, $last_modified, $mime_type);

        $clone = new FileAttributes(
            $given->path(),
            ($given->fileSize() ?? 0) + 1,
            $given->visibility(),
            $given->lastModified(),
            $given->mimeType(),
        );

        $this->assertFalse(FilesystemHelper::isSame($given, $clone));

        $clone = new FileAttributes(
            $given->path(),
            $given->fileSize(),
            ($given->visibility() ?? 'vis') . 'vis',
            $given->lastModified(),
            $given->mimeType(),
        );

        $this->assertFalse(FilesystemHelper::isSame($given, $clone));

        $clone = new FileAttributes(
            $given->path(),
            $given->fileSize(),
            $given->visibility(),
            ($given->lastModified() ?? 0) + 1,
            $given->mimeType(),
        );

        $this->assertFalse(FilesystemHelper::isSame($given, $clone));

        $clone = new FileAttributes(
            $given->path(),
            $given->fileSize(),
            $given->visibility(),
            $given->lastModified(),
            ($given->mimeType() ?? 'mime_type') . '_diff'
        );

        $this->assertFalse(FilesystemHelper::isSame($given, $clone));
    }

    /**
     * @dataProvider provider
     */
    public function testEmptyPathStrings(
        ?int $filesize,
        ?string $visibility,
        ?int $last_modified,
        ?string $mime_type
    ): void {
        $this->checkDifferent('', $filesize, $visibility, $last_modified, $mime_type);
    }

    /**
     * @dataProvider provider
     */
    public function testNumericPaths(
        ?int $filesize,
        ?string $visibility,
        ?int $last_modified,
        ?string $mime_type
    ): void {
        $this->checkDifferent('0', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkDifferent('1', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkDifferent('2', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkDifferent('3', $filesize, $visibility, $last_modified, $mime_type);

        $this->checkDifferent((string) PHP_INT_MAX, $filesize, $visibility, $last_modified, $mime_type);
    }

    /**
     * @dataProvider provider
     */
    public function testPathNames(
        ?int $filesize,
        ?string $visibility,
        ?int $last_modified,
        ?string $mime_type
    ): void {
        $this->checkDifferent('path', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkDifferent('home', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkDifferent('home/directory', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkDifferent('.somewhere', $filesize, $visibility, $last_modified, $mime_type);

        $this->checkDifferent((string) PHP_INT_MAX, $filesize, $visibility, $last_modified, $mime_type);
    }

    public static function provider(): array
    {
        return [
            [null, null, null, null],
            [0, '', 0, ''],
            [500_000_000, 'public', time(), 'application/json'],
            [-1, 'public', -1, 'application/json'],                 // Code does allow negatives
        ];
    }
}
