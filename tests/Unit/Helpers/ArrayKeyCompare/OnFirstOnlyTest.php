<?php

declare(strict_types = 1);

namespace Tests\Unit\Helpers\ArrayKeyCompare;

use TCB\FlysystemSync\Helpers\ArrayKeyCompare;

use function array_fill;

class OnFirstOnlyTest extends \Codeception\Test\Unit
{
    protected readonly ArrayKeyCompare $compare;

    public function setUp(): void
    {
        $this->compare = new ArrayKeyCompare([
            'one'   => 1,
            'two'   => 2,
            'three' => 'nothing',
            ''      => 'empty',
            'deep'  => ['here' => ['again' => null],],
            'again' => ['yup' => [],],
        ], [
            'one-diff'   => 2,
            'two'        => 3,
            'three-diff' => null,
            ''           => false,
            'deep'       => [
                'here' => ['again' => null],
            ],
            'three-deep' => ['again' => []],
        ]);
    }

    public function testOnFirstOnly(): void
    {
        $this->assertEquals([
            'one'   => 1,
            'three' => 'nothing',
            'again' => [
                'yup' => [],
            ],
        ], $this->compare->onFirstOnly());
    }

    public function testOnSecondOnly(): void
    {
        $this->assertEquals([
            'one-diff'   => 2,
            'three-diff' => null,
            'three-deep' => ['again' => []],
        ], $this->compare->onSecondOnly());
    }

    public function testOnBoth(): void
    {
        $this->assertEquals([
            'two'  => 2,
            ''     => 'empty',
            'deep' => ['here' => ['again' => null]],
        ], $this->compare->onBoth());
    }

    public function testAllOnBoth(): void
    {
        $sources = [
            'one'   => 1,
            'two'   => 2,
            'three' => 'nothing',
            ''      => 'empty',
        ];

        $compare = new ArrayKeyCompare($sources, [
            'one'   => 2,
            'two'   => 3,
            'three' => null,
            ''      => false,
        ]);

        $this->assertEquals([], $compare->onFirstOnly());
        $this->assertEquals([], $compare->onSecondOnly());
        $this->assertEquals($sources, $compare->onBoth());
    }

    public function testAllOnBothNumericKeys(): void
    {
        $filled = array_fill(0, 100, null);

        $this->assertEquals($filled, (new ArrayKeyCompare($filled, $filled))->onBoth());
    }
}
