<?php

declare(strict_types = 1);

namespace Tests\Unit\Paths\File;

use TCB\FlysystemSync\Paths\File;

class FilePropertiesTest extends \Codeception\Test\Unit
{
    public function testOnlyPathGiven(): void
    {
        $file = new File('path_only', null, null, null, null);

        $this->assertEquals('path_only', $file->path);
        $this->assertNull($file->visibility);
        $this->assertNull($file->last_modified);
        $this->assertNull($file->file_size);
        $this->assertNull($file->mime_type);

        $this->assertFalse($file->is_directory);
        $this->assertTrue($file->is_file);
    }

    public function testAllGiven(): void
    {
        $file = new File('path_only', 'public', 100_000_000, 1_024, 'application/json');

        $this->assertEquals('path_only', $file->path);
        $this->assertEquals('public', $file->visibility);
        $this->assertEquals(100_000_000, $file->last_modified);
        $this->assertEquals(1_024, $file->file_size);
        $this->assertEquals('application/json', $file->mime_type);

        $this->assertFalse($file->is_directory);
        $this->assertTrue($file->is_file);
    }
}
