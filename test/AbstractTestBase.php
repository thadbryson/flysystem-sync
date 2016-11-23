<?php

namespace Test;

use TCB\Flysystem\Sync;

use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Filesystem;

/**
 * Class AbstractTestBase
 *
 * @author Thad Bryson <thadbry@gmail.com>
 */
class AbstractTestBase extends \PHPUnit_Framework_TestCase
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

        foreach ($files as $file) {

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

        foreach ($files as $file) {

            if (is_dir($file) === true) {
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
}
