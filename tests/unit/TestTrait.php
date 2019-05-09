<?php

declare(strict_types = 1);

namespace Tests\Unit;

use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Filesystem;
use TCB\Flysystem\Sync;
use UnitTester;

trait TestTrait
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * Dir path to 'test/sync-test/'
     *
     * @var string
     */
    protected $testFolder;

    /**
     * Master filesystem.
     *
     * @var Filesystem
     */
    protected $master;

    /**
     * Slave filesystem.
     *
     * @var Filesystem
     */
    protected $slave;

    /**
     * Sync class for Test.
     *
     * @var Sync
     */
    protected $sync;

    public function _before(): void
    {
        $this->testFolder = __DIR__ . '/../_data/sync-test';

        $this->tester->copyDir(__DIR__ . '/../_data/sync-test-seed', $this->testFolder);

        $this->master = new Filesystem(new Adapter(__DIR__ . '/../_data/sync-test/master'));
        $this->slave  = new Filesystem(new Adapter(__DIR__ . '/../_data/sync-test/slave'));

        $this->sync = new Sync($this->master, $this->slave);
    }

    public function _after(): void
    {
        $this->tester->cleanDir($this->testFolder);
    }
}
