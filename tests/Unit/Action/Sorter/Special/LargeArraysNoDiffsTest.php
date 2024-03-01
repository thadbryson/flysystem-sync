<?php

declare(strict_types = 1);

namespace Tests\Unit\Action\Sorter\Special;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action;

class LargeArraysNoDiffsTest extends Unit
{
    public function test1_000Elements(): void
    {
        $array = [];

        for ($i = 1;$i <= 1_000;$i++) {
            $array[$i] = match ($i % 3) {
                0 => null,
                1 => new FileAttributes((string) $i, $i + 1000, 'file', 1_000_000 + $i),
                2 => new DirectoryAttributes((string) $i, 'directory', 1_000_000 + $i)
            };
        }

        $actions = new \TCB\FlysystemSync\Runner\Sorter($array, $array);

        $this->assertEquals([], $actions->create_files);
        $this->assertEquals([], $actions->update_files);
        $this->assertEquals([], $actions->delete_files);

        $this->assertEquals([], $actions->create_directories);
        $this->assertEquals([], $actions->update_directories);
        $this->assertEquals([], $actions->delete_directories);
    }
}
