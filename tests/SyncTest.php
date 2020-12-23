<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

final class SyncTest extends TestCase
{
    use TestTrait;

    /**
     * Test Sync->syncWrites()
     */
    public function testSyncWrites(): void
    {
        $this->sync->syncWrites();

        $this->assertTrue(is_dir($this->output . '/slave/create-dir'));
        $this->assertTrue(is_file($this->output . '/slave/create-dir/1.json'));
        $this->assertTrue(is_file($this->output . '/slave/create-dir/1.txt'));
        $this->assertTrue(is_file($this->output . '/slave/create-dir/2.yml'));
        $this->assertTrue(is_file($this->output . '/slave/create-dir/3.php'));
        $this->assertTrue(is_dir($this->output . '/slave/delete-dir'));
        $this->assertTrue(is_dir($this->output . '/slave/delete-dir/delete-three'));
        $this->assertTrue(is_file($this->output . '/slave/delete-dir/delete-three/huh.txt'));
        $this->assertTrue(is_file($this->output . '/slave/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->output . '/slave/folder1'));
        $this->assertTrue(is_file($this->output . '/slave/folder1/delete.txt'));
        $this->assertTrue(is_file($this->output . '/slave/folder1/master.txt'));
        $this->assertTrue(is_file($this->output . '/slave/folder1/on-both.txt'));
    }

    /**
     * Test Sync->syncDeletes()
     */
    public function testSyncDeletes(): void
    {
        $this->sync->syncDeletes();

        $this->assertFalse(is_dir($this->output . '/slave/create-dir'));
        $this->assertFalse(is_file($this->output . '/slave/create-dir/1.json'));
        $this->assertFalse(is_file($this->output . '/slave/create-dir/1.txt'));
        $this->assertFalse(is_file($this->output . '/slave/create-dir/2.yml'));
        $this->assertFalse(is_file($this->output . '/slave/create-dir/3.php'));
        $this->assertFalse(is_dir($this->output . '/slave/delete-dir'));
        $this->assertFalse(is_dir($this->output . '/slave/delete-dir/delete-three'));
        $this->assertFalse(is_file($this->output . '/slave/delete-dir/delete-three/huh.txt'));
        $this->assertFalse(is_dir($this->output . '/slave/delete-dir/delete-too'));
        $this->assertFalse(is_file($this->output . '/slave/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->output . '/slave/folder1'));
        $this->assertFalse(is_file($this->output . '/slave/folder1/delete.txt'));
        $this->assertFalse(is_file($this->output . '/slave/folder1/master.txt'));
        $this->assertTrue(is_file($this->output . '/slave/folder1/on-both.txt'));
    }

    /**
     * Test Sync->syncUpdates()
     */
    public function testSyncUpdates(): void
    {
        $this->sync->syncUpdates();

        $this->assertFalse(is_dir($this->output . '/slave/create-dir'));
        $this->assertFalse(is_file($this->output . '/slave/create-dir/1.json'));
        $this->assertFalse(is_file($this->output . '/slave/create-dir/1.txt'));
        $this->assertFalse(is_file($this->output . '/slave/create-dir/2.yml'));
        $this->assertFalse(is_file($this->output . '/slave/create-dir/3.php'));
        $this->assertTrue(is_dir($this->output . '/slave/delete-dir'));
        $this->assertTrue(is_dir($this->output . '/slave/delete-dir/delete-three'));
        $this->assertTrue(is_file($this->output . '/slave/delete-dir/delete-three/huh.txt'));
        $this->assertTrue(is_file($this->output . '/slave/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->output . '/slave/folder1'));
        $this->assertTrue(is_file($this->output . '/slave/folder1/delete.txt'));
        $this->assertFalse(is_file($this->output . '/slave/folder1/master.txt'));
        $this->assertTrue(is_file($this->output . '/slave/folder1/on-both.txt'));
    }

    /**
     * Test Sync->sync()
     */
    public function testSync(): void
    {
        $this->sync->sync();

        $this->assertTrue(is_dir($this->output . '/slave/create-dir'));
        $this->assertTrue(is_file($this->output . '/slave/create-dir/1.json'));
        $this->assertTrue(is_file($this->output . '/slave/create-dir/1.txt'));
        $this->assertTrue(is_file($this->output . '/slave/create-dir/2.yml'));
        $this->assertTrue(is_file($this->output . '/slave/create-dir/3.php'));
        $this->assertFalse(is_dir($this->output . '/slave/delete-dir'));
        $this->assertFalse(is_dir($this->output . '/slave/delete-dir/delete-three'));
        $this->assertFalse(is_file($this->output . '/slave/delete-dir/delete-three/huh.txt'));
        $this->assertFalse(is_dir($this->output . '/slave/delete-dir/delete-too'));
        $this->assertFalse(is_file($this->output . '/slave/delete-dir/me1.txt'));
        $this->assertTrue(is_dir($this->output . '/slave/folder1'));
        $this->assertFalse(is_file($this->output . '/slave/folder1/delete.txt'));
        $this->assertTrue(is_file($this->output . '/slave/folder1/master.txt'));
        $this->assertTrue(is_file($this->output . '/slave/folder1/on-both.txt'));
    }
}
