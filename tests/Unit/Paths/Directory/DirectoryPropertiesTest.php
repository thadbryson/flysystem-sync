<?php

declare(strict_types = 1);

namespace Tests\Unit\Paths\Directory;

use TCB\FlysystemSync\Paths\Directory;

class DirectoryPropertiesTest extends \Codeception\Test\Unit
{
    public function testOnlyPathGiven(): void
    {
        $directory = new Directory('path_only', null, null);

        $this->assertEquals('path_only', $directory->path);
        $this->assertNull($directory->visibility);
        $this->assertNull($directory->last_modified);

        $this->assertTrue($directory->is_directory);
        $this->assertFalse($directory->is_file);
    }

    public function testAllGiven(): void
    {
        $directory = new Directory('path_only', 'test-visibility', 100_004_303);

        $this->assertEquals('path_only', $directory->path);
        $this->assertEquals('test-visibility', $directory->visibility);
        $this->assertEquals(100_004_303, $directory->last_modified);

        $this->assertTrue($directory->is_directory);
        $this->assertFalse($directory->is_file);
    }
}
