<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\Filesystem\PreparedPath;

use Codeception\Test\Unit;
use TCB\FlysystemSync\Filesystem\FilesystemHelper;

use function sprintf;

class PreparedPathTest extends Unit
{
    /**
     * @dataProvider providerPaths
     */
    public function testPaths(string $path, string $expected): void
    {
        $prepared = FilesystemHelper::preparePath($path);

        $this->assertEquals($expected, $prepared, sprintf('Paths: "%s" -> "%s"', $path, $prepared));
    }

    public static function providerPaths(): array
    {
        return [
            ['   ', ''],
            ['/', ''],
            ['/some', 'some'],
            ['/here/more/paths', 'here/more/paths'],
            ['/here-path/   ', 'here-path'],
            ['right-slash/   ', 'right-slash'],
        ];
    }

    /**
     * @dataProvider providerNoChanges
     */
    public function testNoChanges(string $path): void
    {
        $prepared = FilesystemHelper::preparePath($path);

        $this->assertEquals($path, $prepared, sprintf('Paths: "%s" -> "%s"', $path, $prepared));
    }

    public static function providerNoChanges(): array
    {
        return [
            [''],
            ['0'],
            ['1'],
            ['something/more/here'],
            ['path/deep/more/here'],
        ];
    }
}
