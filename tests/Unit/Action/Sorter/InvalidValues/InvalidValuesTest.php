<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Sorter\InvalidValues;

use Codeception\Test\Unit;
use DateTime;
use Exception;
use TCB\FlysystemSync\Action;

class InvalidValuesTest extends Unit
{
    public function testInvalidSources(): void
    {
        $this->expectException(Exception::class);
        new Action\Sorter([new DateTime], []);
    }

    public function testInvalidTargets(): void
    {
        $this->expectException(Exception::class);
        new Action\Sorter([], [new DateTime]);
    }

    public function testInvalidSourcesAndTargets(): void
    {
        $this->expectException(Exception::class);
        new Action\Sorter([new DateTime], [new DateTime]);
    }
}
