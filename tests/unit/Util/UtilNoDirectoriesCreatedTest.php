<?php

declare(strict_types = 1);

namespace Tests\Unit\Util;

use PHPUnit\Framework\TestCase;
use Tests\Unit\TestTrait;

final class UtilNoDirectoriesCreatedTest extends TestCase
{
    use TestTrait;

    /**
     * Test Sync->writes
     */
    public function testGetWrites(): void
    {
        $paths = $this->sync->utility->writes;

        $this->assertCount(6, $paths);
        $this->assertEquals('create-dir', $paths['create-dir']->path());
        $this->assertEquals('create-dir/1.json', $paths['create-dir/1.json']->path());
        $this->assertEquals('create-dir/1.txt', $paths['create-dir/1.txt']->path());
        $this->assertEquals('create-dir/2.yml', $paths['create-dir/2.yml']->path());
        $this->assertEquals('create-dir/3.php', $paths['create-dir/3.php']->path());
        $this->assertEquals('folder1/source.txt', $paths['folder1/source.txt']->path());
    }

    /**
     * Test Sync->deletes
     */
    public function testGetDeletes(): void
    {
        $paths = $this->sync->utility->deletes;

        $this->assertCount(6, $paths);
        $this->assertEquals('delete-dir', $paths['delete-dir']->path());
        $this->assertEquals('delete-dir/delete-three', $paths['delete-dir/delete-three']->path());
        $this->assertEquals('delete-dir/delete-three/huh.txt', $paths['delete-dir/delete-three/huh.txt']->path());
        $this->assertEquals('delete-dir/me1.txt', $paths['delete-dir/me1.txt']->path());
        $this->assertEquals('folder1/delete.txt', $paths['folder1/delete.txt']->path());
        $this->assertEquals('folder1/target.txt', $paths['folder1/target.txt']->path());
    }

    /**
     * Test Sync->updates
     */
    public function testGetUpdates(): void
    {
        $paths = $this->sync->utility->updates;

        $this->assertCount(1, $paths);
        $this->assertEquals('folder1/on-both.txt', $paths['folder1/on-both.txt']->path());
    }
}
