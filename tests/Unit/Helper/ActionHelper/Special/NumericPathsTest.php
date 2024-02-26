<?php

declare(strict_types = 1);

namespace Tests\Unit\Helper\ActionHelper\Special;

use Codeception\Test\Unit;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action\ActionHelper;

class NumericPathsTest extends Unit
{
    public function test1_000Elements(): void
    {
        $sources = [
            0   => new FileAttributes('0', 100),
            2   => new FileAttributes('2', 100),
            100 => new DirectoryAttributes('100'),
            55  => new DirectoryAttributes('55'),
            200 => new DirectoryAttributes('200'),
        ];

        $targets = [
            0   => new FileAttributes('0', 103),
            100 => new DirectoryAttributes('100'),
            4   => new FileAttributes('4'),
            7   => new FileAttributes('7'),
            103 => new DirectoryAttributes('103'),
            55  => new DirectoryAttributes('55', 'true'),
        ];

        $actions = new ActionHelper($sources, $targets);

        $this->assertEquals([
            2 => new FileAttributes('2', 100),
        ], $actions->create_files);

        $this->assertEquals([
            0 => new FileAttributes('0', 100),
        ], $actions->update_files);

        $this->assertEquals([
            4 => new FileAttributes('4'),
            7 => new FileAttributes('7'),
        ], $actions->delete_files);

        $this->assertEquals([
            200 => new DirectoryAttributes('200'),
        ], $actions->create_directories);

        $this->assertEquals([
            55 => new DirectoryAttributes('55'),
        ], $actions->update_directories);

        $this->assertEquals([
            103 => new DirectoryAttributes('103'),
        ], $actions->delete_directories);
    }
}
