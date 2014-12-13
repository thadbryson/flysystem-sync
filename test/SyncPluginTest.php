<?php

require_once 'SyncTest.php';

use TCB\Flysystem\Sync;
use TCB\Flysystem\SyncPlugin;

use TCB\FlysystemTest\SyncTest;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;

class SyncPluginTest extends \SyncTest
{
    public function setUp()
    {
        parent::setUp();

        $this->master->addPlugin(new SyncPlugin());
        $this->sync = $this->master->getSync($this->slave);
    }

    public function testGettingSyncFromPlugin()
    {
        $this->assertEquals('TCB\Flysystem\Sync', get_class($this->master->getSync($this->slave)));
    }
}
