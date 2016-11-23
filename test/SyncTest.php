<?php

use TCB\Flysystem\Sync;

use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Filesystem;

/**
 * @author Thad Bryson <thadbry@gmail.com>
 */
class SyncTest extends \PHPUnit_Framework_TestCase
{
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



    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->testFolder = __DIR__ . '/sync-test';

        $this->copyDir(__DIR__ . '/sync-test-seed', __DIR__ . '/sync-test-seed', $this->testFolder);

        $this->master = new Filesystem(new Adapter(__DIR__ . '/sync-test/master'));
        $this->slave  = new Filesystem(new Adapter(__DIR__ . '/sync-test/slave'));

        $this->sync = new Sync($this->master, $this->slave);
    }

    /**
     * Copy one dir to another. Uses SPL.
     *
     * @param $dir
     * @param $src
     * @param $dest
     * @return void
     */
    protected function copyDir($dir, $src, $dest)
    {
        $files = glob("{$dir}/*");

        foreach($files as $file) {

            $destFile = str_replace($src, $dest, $file);

            if (is_dir($file)) {

                if (!is_dir($destFile)) {
                    mkdir($destFile, 0755, true);
                }

                $this->copyDir($file, $src, $dest);

                continue;
            }

            copy($file, $destFile);
        }
    }

    /**
     * Delete a dir. Uses SPL.
     *
     * @param $dir
     * @return void
     */
    protected function deleteDir($dir)
    {
        $files = glob("{$dir}/*");

        foreach($files as $file) {

            if (is_dir($file)) {
                $this->deleteDir($file);

                rmdir($file);

                continue;
            }

            unlink($file);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->deleteDir($this->testFolder);
    }

    /**
     * Test Sync->getWrites()
     *
     * @return void
     */
    public function testGetWrites()
    {
        $paths = $this->sync->getWrites();

        $this->assertEquals(5, count($paths));
        $this->assertEquals('create-dir/1.json', $paths[0]['path']);
        $this->assertEquals('create-dir/1.txt', $paths[1]['path']);
        $this->assertEquals('create-dir/2.yml', $paths[2]['path']);
        $this->assertEquals('create-dir/3.php', $paths[3]['path']);
        $this->assertEquals('folder1/master.txt', $paths[4]['path']);
    }

    /**
     * Test Sync->getDeletes()
     *
     * @return void
     */
    public function testGetDeletes()
    {
        $paths = $this->sync->getDeletes();

        $this->assertEquals(6, count($paths));
        $this->assertEquals('delete-dir', $paths[0]['path']);
        $this->assertEquals('delete-dir/delete-three', $paths[1]['path']);
        $this->assertEquals('delete-dir/delete-three/huh.txt', $paths[2]['path']);
        $this->assertEquals('delete-dir/me1.txt', $paths[3]['path']);
        $this->assertEquals('folder1/delete.txt', $paths[4]['path']);
        $this->assertEquals('folder1/slave.txt', $paths[5]['path']);
    }

    /**
     * Test Sync->getUpdates()
     *
     * @return void
     */
    public function testGetUpdates()
    {
        $paths = $this->sync->getUpdates();

        $this->assertEquals(1, count($paths));
        $this->assertEquals('folder1/on-both.txt', $paths[0]['path']);
    }

    /**
     * Test Sync->syncWrites()
     *
     * @return void
     */
    public function testSyncWrites()
    {
        $this->sync->syncWrites();

        $this->assertTrue(is_dir($this->testFolder  . '/slave/create-dir'));
        $this->assertTrue(is_file($this->testFolder . '/slave/create-dir/1.json'));
        $this->assertTrue(is_file($this->testFolder . '/slave/create-dir/1.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/create-dir/2.yml'));
        $this->assertTrue(is_file($this->testFolder . '/slave/create-dir/3.php'));
        $this->assertTrue(is_dir($this->testFolder  . '/slave/delete-dir'));
        $this->assertTrue(is_dir($this->testFolder  . '/slave/delete-dir/delete-three'));
        $this->assertTrue(is_file($this->testFolder . '/slave/delete-dir/delete-three/huh.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->testFolder  . '/slave/folder1'));
        $this->assertTrue(is_file($this->testFolder . '/slave/folder1/delete.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/folder1/master.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/folder1/on-both.txt'));
    }

    /**
     * Test Sync->syncDeletes()
     *
     * @return void
     */
    public function testSyncDeletes()
    {
        $this->sync->syncDeletes();

        $this->assertFalse(is_dir($this->testFolder  . '/slave/create-dir'));
        $this->assertFalse(is_file($this->testFolder . '/slave/create-dir/1.json'));
        $this->assertFalse(is_file($this->testFolder . '/slave/create-dir/1.txt'));
        $this->assertFalse(is_file($this->testFolder . '/slave/create-dir/2.yml'));
        $this->assertFalse(is_file($this->testFolder . '/slave/create-dir/3.php'));
        $this->assertFalse(is_dir($this->testFolder  . '/slave/delete-dir'));
        $this->assertFalse(is_dir($this->testFolder  . '/slave/delete-dir/delete-three'));
        $this->assertFalse(is_file($this->testFolder . '/slave/delete-dir/delete-three/huh.txt'));
        $this->assertFalse(is_dir($this->testFolder  . '/slave/delete-dir/delete-too'));
        $this->assertFalse(is_file($this->testFolder . '/slave/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->testFolder  . '/slave/folder1'));
        $this->assertFalse(is_file($this->testFolder . '/slave/folder1/delete.txt'));
        $this->assertFalse(is_file($this->testFolder . '/slave/folder1/master.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/folder1/on-both.txt'));
    }

    /**
     * Test Sync->syncUpdates()
     *
     * @return void
     */
    public function testSyncUpdates()
    {
        $this->sync->syncUpdates();

        $this->assertFalse(is_dir($this->testFolder  . '/slave/create-dir'));
        $this->assertFalse(is_file($this->testFolder . '/slave/create-dir/1.json'));
        $this->assertFalse(is_file($this->testFolder . '/slave/create-dir/1.txt'));
        $this->assertFalse(is_file($this->testFolder . '/slave/create-dir/2.yml'));
        $this->assertFalse(is_file($this->testFolder . '/slave/create-dir/3.php'));
        $this->assertTrue(is_dir($this->testFolder  . '/slave/delete-dir'));
        $this->assertTrue(is_dir($this->testFolder  . '/slave/delete-dir/delete-three'));
        $this->assertTrue(is_file($this->testFolder . '/slave/delete-dir/delete-three/huh.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->testFolder  . '/slave/folder1'));
        $this->assertTrue(is_file($this->testFolder . '/slave/folder1/delete.txt'));
        $this->assertFalse(is_file($this->testFolder . '/slave/folder1/master.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/folder1/on-both.txt'));
    }

    /**
     * Test Sync->sync()
     *
     * @return void
     */
    public function testSync()
    {
        $this->sync->sync();

        $this->assertTrue(is_dir($this->testFolder  . '/slave/create-dir'));
        $this->assertTrue(is_file($this->testFolder . '/slave/create-dir/1.json'));
        $this->assertTrue(is_file($this->testFolder . '/slave/create-dir/1.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/create-dir/2.yml'));
        $this->assertTrue(is_file($this->testFolder . '/slave/create-dir/3.php'));
        $this->assertFalse(is_dir($this->testFolder  . '/slave/delete-dir'));
        $this->assertFalse(is_dir($this->testFolder  . '/slave/delete-dir/delete-three'));
        $this->assertFalse(is_file($this->testFolder . '/slave/delete-dir/delete-three/huh.txt'));
        $this->assertFalse(is_dir($this->testFolder  . '/slave/delete-dir/delete-too'));
        $this->assertFalse(is_file($this->testFolder . '/slave/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->testFolder  . '/slave/folder1'));
        $this->assertFalse(is_file($this->testFolder . '/slave/folder1/delete.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/folder1/master.txt'));
        $this->assertTrue(is_file($this->testFolder . '/slave/folder1/on-both.txt'));
    }
}
