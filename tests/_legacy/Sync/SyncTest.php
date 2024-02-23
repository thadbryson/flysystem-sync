<?php

declare(strict_types = 1);

namespace Tests\_legacy\Sync;

use PHPUnit\Framework\TestCase;
use Tests\_legacy\TestTrait;

final class SyncTest extends TestCase
{
    use TestTrait;

    /**
     * Test Sync->syncWrites()
     */
    public function testSyncWrites(): void
    {
        $this->sync->syncWrites();

        $this->assertTrue(is_dir($this->output . '/target/create-dir'));
        $this->assertTrue(is_file($this->output . '/target/create-dir/1.json'));
        $this->assertTrue(is_file($this->output . '/target/create-dir/1.txt'));
        $this->assertTrue(is_file($this->output . '/target/create-dir/2.yml'));
        $this->assertTrue(is_file($this->output . '/target/create-dir/3.php'));
        $this->assertTrue(is_dir($this->output . '/target/delete-dir'));
        $this->assertTrue(is_dir($this->output . '/target/delete-dir/delete-three'));
        $this->assertTrue(is_file($this->output . '/target/delete-dir/delete-three/huh.txt'));
        $this->assertTrue(is_file($this->output . '/target/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->output . '/target/folder1'));
        $this->assertTrue(is_file($this->output . '/target/folder1/delete.txt'));
        $this->assertTrue(is_file($this->output . '/target/folder1/source.txt'));
        $this->assertTrue(is_file($this->output . '/target/folder1/on-both.txt'));
    }

    /**
     * Test Sync->syncDeletes()
     */
    public function testSyncDeletes(): void
    {
        $this->sync->syncDeletes();

        $this->assertFalse(is_dir($this->output . '/target/create-dir'));
        $this->assertFalse(is_file($this->output . '/target/create-dir/1.json'));
        $this->assertFalse(is_file($this->output . '/target/create-dir/1.txt'));
        $this->assertFalse(is_file($this->output . '/target/create-dir/2.yml'));
        $this->assertFalse(is_file($this->output . '/target/create-dir/3.php'));
        $this->assertFalse(is_dir($this->output . '/target/delete-dir'));
        $this->assertFalse(is_dir($this->output . '/target/delete-dir/delete-three'));
        $this->assertFalse(is_file($this->output . '/target/delete-dir/delete-three/huh.txt'));
        $this->assertFalse(is_dir($this->output . '/target/delete-dir/delete-too'));
        $this->assertFalse(is_file($this->output . '/target/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->output . '/target/folder1'));
        $this->assertFalse(is_file($this->output . '/target/folder1/delete.txt'));
        $this->assertFalse(is_file($this->output . '/target/folder1/source.txt'));
        $this->assertTrue(is_file($this->output . '/target/folder1/on-both.txt'));
    }

    /**
     * Test Sync->syncUpdates()
     */
    public function testSyncUpdates(): void
    {
        $this->sync->syncUpdates();

        $this->assertFalse(is_dir($this->output . '/target/create-dir'));
        $this->assertFalse(is_file($this->output . '/target/create-dir/1.json'));
        $this->assertFalse(is_file($this->output . '/target/create-dir/1.txt'));
        $this->assertFalse(is_file($this->output . '/target/create-dir/2.yml'));
        $this->assertFalse(is_file($this->output . '/target/create-dir/3.php'));
        $this->assertTrue(is_dir($this->output . '/target/delete-dir'));
        $this->assertTrue(is_dir($this->output . '/target/delete-dir/delete-three'));
        $this->assertTrue(is_file($this->output . '/target/delete-dir/delete-three/huh.txt'));
        $this->assertTrue(is_file($this->output . '/target/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->output . '/target/folder1'));
        $this->assertTrue(is_file($this->output . '/target/folder1/delete.txt'));
        $this->assertFalse(is_file($this->output . '/target/folder1/source.txt'));
        $this->assertTrue(is_file($this->output . '/target/folder1/on-both.txt'));
    }

    /**
     * Test Sync->sync()
     */
    public function testSync(): void
    {
        $this->sync->sync();

        $this->assertTrue(is_dir($this->output . '/target/create-dir'));
        $this->assertTrue(is_file($this->output . '/target/create-dir/1.json'));
        $this->assertTrue(is_file($this->output . '/target/create-dir/1.txt'));
        $this->assertTrue(is_file($this->output . '/target/create-dir/2.yml'));
        $this->assertTrue(is_file($this->output . '/target/create-dir/3.php'));
        $this->assertFalse(is_dir($this->output . '/target/delete-dir'));
        $this->assertFalse(is_dir($this->output . '/target/delete-dir/delete-three'));
        $this->assertFalse(is_file($this->output . '/target/delete-dir/delete-three/huh.txt'));
        $this->assertFalse(is_dir($this->output . '/target/delete-dir/delete-too'));
        $this->assertFalse(is_file($this->output . '/target/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->output . '/target/folder1'));
        $this->assertFalse(is_file($this->output . '/target/folder1/delete.txt'));
        $this->assertTrue(is_file($this->output . '/target/folder1/source.txt'));
        $this->assertTrue(is_file($this->output . '/target/folder1/on-both.txt'));
    }
}
