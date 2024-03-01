<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem\Helper\IsSame\Files;

use Codeception\Test\Unit;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;

use function time;

use const PHP_INT_MAX;

class SameTest extends Unit
{
    protected function checkSame(
        string $path,
        ?int $filesize,
        ?string $visibility,
        ?int $last_modified,
        ?string $mime_type
    ): void {
        $given = new FileAttributes($path, $filesize, $visibility, $last_modified, $mime_type);

        $clone = new FileAttributes(
            $given->path(),
            $given->fileSize(),
            $given->visibility(),
            $given->lastModified(),
            $given->mimeType()
        );

        $this->assertTrue(HelperFilesystem::isSame($given, $clone), 'Passed in same variable');
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
        $this->checkSame('', $filesize, $visibility, $last_modified, $mime_type);
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
        $this->checkSame('0', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkSame('1', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkSame('2', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkSame('3', $filesize, $visibility, $last_modified, $mime_type);

        $this->checkSame((string) PHP_INT_MAX, $filesize, $visibility, $last_modified, $mime_type);
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
        $this->checkSame('path', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkSame('home', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkSame('home/directory', $filesize, $visibility, $last_modified, $mime_type);
        $this->checkSame('.somewhere', $filesize, $visibility, $last_modified, $mime_type);

        $this->checkSame((string) PHP_INT_MAX, $filesize, $visibility, $last_modified, $mime_type);
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
