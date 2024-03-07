<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem\Reader;

use League\Flysystem\FilesystemReader;
use ReflectionClass;
use TCB\FlysystemSync\Filesystem\Reader;
use Tests\Support\UnitTester;

class ReaderHasOnlyReadMethodsCest
{
    public function _before(UnitTester $I)
    {
    }

    public function FilesystemReaderHasOnlyReadMethods(UnitTester $I)
    {
        $I->assertEquals([
            FilesystemReader::class,
        ], class_implements(Reader::class), 'Should only implement FilesystemReader interface');

        $reader    = (new ReflectionClass(Reader::class))->getMethods();
        $interface = (new ReflectionClass(FilesystemReader::class))->getMethods();
    }
}
