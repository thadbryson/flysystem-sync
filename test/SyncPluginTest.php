<?php

namespace Test;

use TCB\Flysystem\SyncPlugin;

/**
 * Class SyncPluginTest
 *
 * @author Thad Bryson <thadbry@gmail.com>
 */
class SyncPluginTest extends SyncTest
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->master->addPlugin(new SyncPlugin());
        $this->sync = $this->master->getSync($this->slave);
    }

    /**
     * Get Sync object from plugin object.
     *
     * @return void
     */
    public function testGettingSyncFromPlugin()
    {
        $this->assertEquals('TCB\Flysystem\Sync', get_class($this->master->getSync($this->slave)));
    }
}
