<?php

declare(strict_types = 1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use TCB\FlysystemSync\Sync;

final class UtilTest extends TestCase
{
    use TestTrait;

    /**
     * Test Sync->getWrites()
     */
    public function testGetWrites(): void
    {
        $paths = $this->sync->getUtil()->getWrites();

        $this->assertCount(6, $paths);
        $this->assertEquals('create-dir', $paths['create-dir']->path());
        $this->assertEquals('create-dir/1.json', $paths['create-dir/1.json']->path());
        $this->assertEquals('create-dir/1.txt', $paths['create-dir/1.txt']->path());
        $this->assertEquals('create-dir/2.yml', $paths['create-dir/2.yml']->path());
        $this->assertEquals('create-dir/3.php', $paths['create-dir/3.php']->path());
        $this->assertEquals('folder1/master.txt', $paths['folder1/master.txt']->path());
    }

    /**
     * Test Sync->getDeletes()
     */
    public function testGetDeletes(): void
    {
        $paths = $this->sync->getUtil()->getDeletes();

        $this->assertCount(6, $paths);
        $this->assertEquals('delete-dir', $paths['delete-dir']->path());
        $this->assertEquals('delete-dir/delete-three', $paths['delete-dir/delete-three']->path());
        $this->assertEquals('delete-dir/delete-three/huh.txt', $paths['delete-dir/delete-three/huh.txt']->path());
        $this->assertEquals('delete-dir/me1.txt', $paths['delete-dir/me1.txt']->path());
        $this->assertEquals('folder1/delete.txt', $paths['folder1/delete.txt']->path());
        $this->assertEquals('folder1/slave.txt', $paths['folder1/slave.txt']->path());
    }

    /**
     * Test Sync->getUpdates()
     */
    public function testGetUpdates(): void
    {
        $paths = $this->sync->getUtil()->getUpdates();

        $this->assertCount(1, $paths);
        $this->assertEquals('folder1/on-both.txt', $paths['folder1/on-both.txt']->path());
    }

    /**
     * Test Sync->getUpdates() with modification time
     * The modification time on slave is always different, because it is the time the file is uploaded to the slave
     */
    public function testGetUpdatesWithModification(): void
    {
        $master = $this->sync->getMaster();
        $slave = $this->sync->getSlave();

        $lastModified = $master->lastModified('folder1/on-both-same.txt');
        sleep(1);
        $master->write('folder1/on-both-same.txt', '');

        $this->assertNotSame($lastModified, $master->lastModified('folder1/on-both-same.txt'));

        $sync = new Sync($master, $slave);
        $paths = $sync->getUtil()->getUpdates();

        $this->assertCount(1, $paths);
        $this->assertEquals('folder1/on-both.txt', $paths['folder1/on-both.txt']->path());
        $this->assertNotContains('folder1/on-both-same.txt', $paths);
    }
}
