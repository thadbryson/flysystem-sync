<?php

declare(strict_types = 1);

namespace Unit\Collector;

use Exception;
use TCB\FlysystemSync\Collection;

class CollectorBasicTest extends \Codeception\Test\Unit
{
    protected readonly Collection $collection;

    public function setUp(): void
    {
        $collection = new Collection;
        $collection
            ->add('/path1')         // left-trim will convert to "path1"
            ->add('/////path2/////')
            ->add('path3/')
            ->add('path4')
            ->add('dir')        // Will left-trim any amount of /
            ->add('dir-again');

        $this->collection = $collection;
    }

    public function testBasics(): void
    {
        $this->assertEquals([
            'path1',
            'path2',
            'path3',
            'path4',
            'dir',
            'dir-again',
        ], $this->collection->all(), '->all()');
    }

    public function testHasPath(): void
    {
        $this->assertTrue($this->collection->has('path1'));
        $this->assertTrue($this->collection->has('path2'));
        $this->assertTrue($this->collection->has('path3'));
        $this->assertTrue($this->collection->has('path4'));
        $this->assertTrue($this->collection->has('dir'));
        $this->assertTrue($this->collection->has('dir-again'));

        $this->assertTrue($this->collection->has('/path1'));
        $this->assertTrue($this->collection->has('/path2'));
        $this->assertTrue($this->collection->has('/path3'));
        $this->assertTrue($this->collection->has('/path4/'));
        $this->assertTrue($this->collection->has('/dir/'));
        $this->assertTrue($this->collection->has('/////dir-again//////'));
    }

    public function testExceptionPathWasAlreadyAdded(): void
    {
        $this->expectException(Exception::class);

        $this->collection->add('path1');
    }
}
