<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Helper\InvalidValues;

use Codeception\Test\Unit;
use Exception;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;

class DifferentPathsSetTest extends Unit
{
    public function testDifferentOnSources(): void
    {
        $this->expectException(Exception::class);

        new Action\Sorter(
            ['path' => new FileAttributes('path-1')],
            ['path' => new FileAttributes('path')]
        );
    }

    public function testDifferentOnTargets(): void
    {
        $this->expectException(Exception::class);

        new Action\Sorter(
            ['path' => new FileAttributes('path')],
            ['path' => new FileAttributes('path-diff')]
        );
    }

    public function testDiffEndsSlash(): void
    {
        $this->expectException(Exception::class);

        new Action\Sorter(
            ['path' => new FileAttributes('path/')],
            []
        );
    }

    /**
     * Cannot have different path strings for key and ->path()
     * NOTE: They're prepared before getting here.
     *
     * @throws Exception
     */
    public function testWhitespace(): void
    {
        $this->expectException(Exception::class);

        new Action\Sorter(
            ['path ' => new FileAttributes('path')],
            []
        );
    }
}
