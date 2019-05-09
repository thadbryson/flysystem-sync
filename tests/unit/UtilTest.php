<?php

declare(strict_types = 1);

namespace Tests\Unit;

class UtilTest extends \Codeception\Test\Unit
{
    use TestTrait;

    /**
     * Test Sync->getWrites()
     *
     * @return void
     */
    public function testGetWrites(): void
    {
        $paths = $this->sync->getUtil()->getWrites();

        $this->assertEquals(6, count($paths));
        $this->assertEquals('create-dir', $paths['create-dir']['path']);
        $this->assertEquals('create-dir/1.json', $paths['create-dir/1.json']['path']);
        $this->assertEquals('create-dir/1.txt', $paths['create-dir/1.txt']['path']);
        $this->assertEquals('create-dir/2.yml', $paths['create-dir/2.yml']['path']);
        $this->assertEquals('create-dir/3.php', $paths['create-dir/3.php']['path']);
        $this->assertEquals('folder1/master.txt', $paths['folder1/master.txt']['path']);
    }

    /**
     * Test Sync->getDeletes()
     *
     * @return void
     */
    public function testGetDeletes(): void
    {
        $paths = $this->sync->getUtil()->getDeletes();

        $this->assertEquals(6, count($paths));
        $this->assertEquals('delete-dir', $paths['delete-dir']['path']);
        $this->assertEquals('delete-dir/delete-three', $paths['delete-dir/delete-three']['path']);
        $this->assertEquals('delete-dir/delete-three/huh.txt', $paths['delete-dir/delete-three/huh.txt']['path']);
        $this->assertEquals('delete-dir/me1.txt', $paths['delete-dir/me1.txt']['path']);
        $this->assertEquals('folder1/delete.txt', $paths['folder1/delete.txt']['path']);
        $this->assertEquals('folder1/slave.txt', $paths['folder1/slave.txt']['path']);
    }

    /**
     * Test Sync->getUpdates()
     *
     * @return void
     */
    public function testGetUpdates(): void
    {
        $paths = $this->sync->getUtil()->getUpdates();

        $this->assertEquals(1, count($paths));
        $this->assertEquals('folder1/on-both.txt', $paths['folder1/on-both.txt']['path']);
    }
}
