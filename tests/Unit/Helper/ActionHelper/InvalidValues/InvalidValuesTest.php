<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\ActionHelper\InvalidValues;

use Codeception\Test\Unit;
use DateTime;
use TCB\FlysystemSync\Action\ActionHelper;

class InvalidValuesTest extends Unit
{
    public function testInvalidSources(): void
    {
        $this->expectException(\Exception::class);
        new ActionHelper([new DateTime], []);
    }

    public function testInvalidTargets(): void
    {
        $this->expectException(\Exception::class);
        new ActionHelper([], [new DateTime]);
    }

    public function testInvalidSourcesAndTargets(): void
    {
        $this->expectException(\Exception::class);
        new ActionHelper([new DateTime], [new DateTime]);
    }
}
