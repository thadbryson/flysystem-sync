<?php

declare(strict_types = 1);

namespace Tests\Unit\Helpers\ArrayKeyCompare;

use TCB\FlysystemSync\Helpers\ArrayKeyCompare;

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
}
