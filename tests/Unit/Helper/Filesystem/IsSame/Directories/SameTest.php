<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\Filesystem\IsSame\Directories;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use TCB\FlysystemSync\Helper\FilesystemHelper;

use function time;

use const PHP_INT_MAX;

class SameTest extends Unit
{
    protected function checkSame(string $path, ?string $visibility, ?int $last_modified): void
    {
        $given = new DirectoryAttributes($path, $visibility, $last_modified);

        $clone = new DirectoryAttributes(
            $given->path(),
            $given->visibility(),
            $given->lastModified(),
        );

        $this->assertTrue(FilesystemHelper::isSame($given, $clone), 'Passed in same variable');
    }

    /**
     * @dataProvider provider
     */
    public function testEmptyPathStrings(?string $visibility, ?int $last_modified,): void
    {
        $this->checkSame('', $visibility, $last_modified);
    }

    /**
     * @dataProvider provider
     */
    public function testNumericPaths(?string $visibility, ?int $last_modified): void
    {
        $this->checkSame('0', $visibility, $last_modified);
        $this->checkSame('1', $visibility, $last_modified);
        $this->checkSame('2', $visibility, $last_modified);
        $this->checkSame('3', $visibility, $last_modified);

        $this->checkSame((string) PHP_INT_MAX, $visibility, $last_modified);
    }

    /**
     * @dataProvider provider
     */
    public function testPathNames(?string $visibility, ?int $last_modified,): void
    {
        $this->checkSame('path', $visibility, $last_modified);
        $this->checkSame('home', $visibility, $last_modified);
        $this->checkSame('home/directory', $visibility, $last_modified);
        $this->checkSame('.somewhere', $visibility, $last_modified);

        $this->checkSame((string) PHP_INT_MAX, $visibility, $last_modified);
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
