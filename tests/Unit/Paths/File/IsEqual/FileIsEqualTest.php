<?php

declare(strict_types = 1);

namespace Tests\Unit\Paths\File\IsEqual;

use TCB\FlysystemSync\Paths\Directory;

class FileIsEqualTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider providerIsSameTrue
     * @dataProvider providerIsEqualsFalse
     */
    public function testIsSameTrue(
        bool $expected,
        string $path1,
        ?string $visibility1,
        ?int $last_modified1,
        string $path2,
        ?string $visibility2,
        ?int $last_modified2
    ): void {
        $directory1 = new Directory($path1, $visibility1, $last_modified1);
        $directory2 = new Directory($path2, $visibility2, $last_modified2);

        $visibility1 = $visibility1 === null ? 'NULL' : "'" . $visibility1 . "'";
        $visibility2 = $visibility2 === null ? 'NULL' : "'" . $visibility2 . "'";

        $last_modified1 = $last_modified1 === null ? 'NULL' : "'" . $last_modified1 . "'";
        $last_modified2 = $last_modified2 === null ? 'NULL' : "'" . $last_modified2 . "'";

        $message = sprintf("
%s Expected
Path:        \"%s\" != \"%s\"
Visibility:    %s != %s
Last Modified: %s != %s",
            $expected ? 'TRUE' : 'FALSE',
            $path1, $path2,
            $visibility1, $visibility2,
            $last_modified1, $last_modified2);

        $this->assertEquals($expected, $directory1->isEqual($directory2), $message);
    }

    public function providerIsSameTrue(): array
    {
        return [
            // Only paths given, they ===
            [true, 'path', null, null, 'path', null, null],
            [true, 'path/', null, null, 'path/', null, null],
            [true, '/path', null, null, '/path', null, null],
            [true, '/path/', null, null, '/path/', null, null],

            // Starting / does not matter
            [true, '/path', null, null, 'path', null, null],
            [true, '/path/', null, null, 'path/', null, null],
            [true, 'path', null, null, '/path', null, null],
            [true, 'path/', null, null, '/path/', null, null],

            // All properties, path, visibility, last_modified
            [true, 'path', 'priv', 5, 'path', 'priv', 5],
            [true, 'path', 'priv', -10, 'path', 'priv', -10],
            [true, '/path', 'priv', 1, '/path', 'priv', 1],
            [true, 'path/', 'priv', 1, 'path/', 'priv', 1],
            [true, '/path/', 'priv', 1, '/path/', 'priv', 1],
        ];
    }

    public function providerIsEqualsFalse(): array
    {
        return [
            // Only paths given
            [false, 'path', null, null, 'path2', null, null],
            [false, 'path3/deep', null, null, 'path3/', null, null],
            [false, '/path1', null, null, 'path', null, null],       // Diff: leading "/"
            [false, '/path-somewhere-else', null, null, '/path/', null, null],

            // All given, same path, NULL for 1 other property
            [false, 'path', 'priv', 5, 'path', 'priv', null],
            [false, 'path', 'priv', -10, 'path', null, -10],

            // All given, same path, different values
            [false, '/path', 'priv', 1, '/path', 'priv', 2],
            [false, 'path/', 'priv~other', 1, 'path/', 'priv', 1],
        ];
    }
}
