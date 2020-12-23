<?php

declare(strict_types = 1);

namespace Tests;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter as Adapter;
use TCB\FlysystemSync\Sync;

trait TestTrait
{
    /**
     * Dir path to 'tests/sync-test/'
     *
     * @var string
     */
    protected $output;

    /**
     * Sync class for Test.
     *
     * @var Sync
     */
    protected $sync;

    public function setUp(): void
    {
        $this->output = __DIR__ . '/sync-test';
        $this->deleteDirectory($this->output);

        $this->copyDir(__DIR__ . '/sync-test-seed', __DIR__ . '/sync-test-seed', $this->output);

        $master = new Filesystem(new Adapter(__DIR__ . '/sync-test/master'));
        $slave  = new Filesystem(new Adapter(__DIR__ . '/sync-test/slave'));

        $this->sync = new Sync($master, $slave);
    }

    /**
     * Copy one dir to another. Uses SPL.
     */
    protected function copyDir(string $dir, string $src, string $dest): void
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
     */
    protected function deleteDirectory(string $dir): void
    {
        $files = glob("{$dir}/*");

        foreach ($files as $file) {

            if (is_dir($file) === true) {
                $this->deleteDirectory($file);

                rmdir($file);

                continue;
            }

            unlink($file);
        }
    }
}
