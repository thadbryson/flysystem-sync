<?php

declare(strict_types = 1);

namespace Tests\Unit\Paths\Collector;

use Exception;
use TCB\FlysystemSync\Paths\Collection;

class CollectorBasicTest extends \Codeception\Test\Unit
{
    protected readonly Collection $collection;

    public function setUp(): void
    {
        $collection = new Collection;
        $collection->file('path1');
        $collection->file('path2');
        $collection->file('path3');
        $collection->file('path4');

        $collection->directory('dir');
        $collection->directory('dir-again');

        $this->collection = $collection;
    }

    public function testBasics(): void
    {
        $this->assertEquals([
            'path1',
            'path2',
            'path3',
            'path4',
        ], $this->collection->getFiles(), '->files()');

        $this->assertEquals([
            'dir',
            'dir-again',
        ], $this->collection->getDirectories(), '->directories()');

        $this->assertEquals([
            'path1',
            'path2',
            'path3',
            'path4',
            'dir',
            'dir-again',
        ], $this->collection->all(), '->all()');
    }

    public function testExceptionEmptyFilePathString(): void
    {
        $this->expectException(Exception::class);
        $this->collection->file('');
    }

    public function testExceptionEmptyDirectoryPathString(): void
    {
        $this->expectException(Exception::class);
        $this->collection->directory('');
    }

    public function testCollisionFileName(): void
    {
        $this->expectException(Exception::class);
        $this->collection->file('path1');
    }

    public function testCollisionDirectoryName(): void
    {
        $this->expectException(Exception::class);
        $this->collection->directory('dir');
    }
}
