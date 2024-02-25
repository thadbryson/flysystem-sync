<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\ActionHelper\Sames\DifferentOrder;

use Tests\Unit\Helper\ActionHelper\Sames\InOrder\AllUpdatesTest as Extended;

class AllUpdatesTest extends Extended
{
    public function testAllUpdates(): void
    {
        $this->assertEquals([], $this->actions->create_files);
        $this->assertEquals([], $this->actions->update_files);
        $this->assertEquals([], $this->actions->delete_files);

        $this->assertEquals([], $this->actions->create_directories);
        $this->assertEquals([], $this->actions->update_directories);
        $this->assertEquals([], $this->actions->delete_directories);
    }
}
