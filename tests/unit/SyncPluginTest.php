<?php

declare(strict_types = 1);

namespace Tests\Unit;

use TCB\Flysystem\Sync;
use TCB\Flysystem\SyncPlugin;

class SyncPluginTest extends SyncTest
{
    use TestTrait;

    public function _before(): void
    {
        parent::_before();

        $this->master->addPlugin(new SyncPlugin());

        $this->sync = $this->master->getSync($this->slave);
    }

    /**
     * Get Sync object from plugin object.
     *
     * @return void
     */
    public function testGettingSyncFromPlugin(): void
    {
        $this->assertEquals(Sync::class, get_class($this->master->getSync($this->slave)));
    }
}
