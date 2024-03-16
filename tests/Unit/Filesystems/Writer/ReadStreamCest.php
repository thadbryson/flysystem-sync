<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems\Writer;

use Exception;
use Tests\Support\UnitTester;
use Tests\Unit\Filesystems\WriterTrait;

class ReadStreamCest
{
    use WriterTrait;

    public function readStream(UnitTester $I): void
    {
        $I->expectThrowable(new Exception(''), function () {
            $this->sync_filesystem->readStream('home/user.txt');
        });
    }
}
