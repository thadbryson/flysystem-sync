<?php

declare(strict_types = 1);

namespace Tests\Unit\Helpers\ArrayKeyCompare;

use TCB\FlysystemSync\Helpers\ArrayKeyCompare;

use function array_fill;

class OnBothWhenTest extends \Codeception\Test\Unit
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

    public function testAllOnBothNumericKeys(): void
    {
        $filled = array_fill(0, 20, null);

        $compare = new ArrayKeyCompare($filled, $filled);

        $when = fn (string|int $key): bool => $key % 2 === 0;

        $both_when = $compare->onBothWhen($when);

        $this->assertEquals([
            0  => null,
            2  => null,
            4  => null,
            6  => null,
            8  => null,
            10 => null,
            12 => null,
            14 => null,
            16 => null,
            18 => null,
        ], $both_when);
    }
}
