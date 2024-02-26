<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\Filesystem\IsSame\Directories;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use TCB\FlysystemSync\Filesystem\FilesystemHelper;

use function time;

use const PHP_INT_MAX;

class DiffTest extends Unit
{
    protected function checkDifferent(string $path, ?string $visibility, ?int $last_modified): void
    {
        $given = new DirectoryAttributes($path, $visibility, $last_modified);

        $clone = new DirectoryAttributes(
            $given->path(),
            ($given->visibility() ?? 'vis') . 'vis',
            $given->lastModified(),
        );

        $this->assertFalse(FilesystemHelper::isSame($given, $clone));

        $clone = new DirectoryAttributes(
            $given->path(),
            $given->visibility(),
            ($given->lastModified() ?? 0) + 1,
        );

        $this->assertFalse(FilesystemHelper::isSame($given, $clone));
    }

    /**
     * @dataProvider provider
     */
    public function testEmptyPathStrings(?string $visibility, ?int $last_modified): void
    {
        $this->checkDifferent('', $visibility, $last_modified);
    }

    /**
     * @dataProvider provider
     */
    public function testNumericPaths(?string $visibility, ?int $last_modified,): void
    {
        $this->checkDifferent('0', $visibility, $last_modified);
        $this->checkDifferent('1', $visibility, $last_modified);
        $this->checkDifferent('2', $visibility, $last_modified);
        $this->checkDifferent('3', $visibility, $last_modified);

        $this->checkDifferent((string) PHP_INT_MAX, $visibility, $last_modified);
    }

    /**
     * @dataProvider provider
     */
    public function testPathNames(?string $visibility, ?int $last_modified): void
    {
        $this->checkDifferent('path', $visibility, $last_modified);
        $this->checkDifferent('home', $visibility, $last_modified);
        $this->checkDifferent('home/directory', $visibility, $last_modified);
        $this->checkDifferent('.somewhere', $visibility, $last_modified);

        $this->checkDifferent((string) PHP_INT_MAX, $visibility, $last_modified);
    }

    public static function provider(): array
    {
        return [
            [null, null],
            ['', 0],
            ['public', time()],
            ['public', -1],                 // Code does allow negatives
        ];
    }
}
