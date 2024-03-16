<?php

declare(strict_types = 1);

namespace Tests\Unit\Paths\File;

use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;
use Tests\Support\UnitTester;

class FileCompareCest
{
    protected File $file;

    public function _before(): void
    {
        $this->file = $this->makeBasic();
    }

    protected function makeBasic(): File
    {
        return new File('path', 'public', 1_000, 1_000_000, 'application/json');
    }

    public function whenTargetIsNullTrue(UnitTester $I): void
    {
        $is_changed = $this->file->isChanged(null);

        $I->assertTrue($is_changed, 'When TARGET is NULL, always CHANGED');
    }

    public function whenTargetTypeIsDifferentTrue(UnitTester $I): void
    {
        $is_changed = $this->file->isChanged(
            new Directory('path', 'public', 1_000)
        );

        $I->assertTrue($is_changed, 'When TARGET is different, always CHANGED');
    }

    public function whenExactlySamePaths(UnitTester $I): void
    {
        $file1 = $this->makeBasic();
        $file2 = $this->makeBasic();

        $I->assertFalse($file1->isChanged($file2));
    }

    public function hasDifferentVisibility(UnitTester $I): void
    {
        // visibility = "public"
        foreach ([
            '',
            'private',
            'a',
            '1',
            '0',
            'null',
            'true',
            'false',
            'PUBLIC',
            'Public',
        ] as $visibility) {
            $compare = new File('path', $visibility, 1_000, 1_000_000, 'application/json');

            $I->assertTrue($this->file->isChanged($compare));
        }
    }

    /**
     * Only visibility changes ->isChanged())
     */
    public function lastModifiedBeforeSource(UnitTester $I): void
    {
        // lastModified = 1,000
        foreach ([
            0,
            1,
            999,
            -1,
            -1_000,
        ] as $before_source) {
            $compare = new File('path', 'public', $before_source, 1_000_000, 'application/json');

            $I->assertTrue($this->file->isChanged($compare));
        }
    }

    public function lastModifiedAfterSource(UnitTester $I): void
    {
        // lastModified = 1,000
        foreach ([
            1_001,
            10_000,
            100_000,
            100_000_000,
        ] as $before_source) {
            $compare = new File('path', 'public', $before_source, 1_000_000, 'application/json');

            $I->assertFalse($this->file->isChanged($compare));
        }
    }

    public function differentFilesizes(UnitTester $I): void
    {
        foreach ([
            0,
            1,
            1_000,
        ] as $filesize) {
            $compare = new File('path', 'public', 1_000, $filesize, 'application/json');

            $I->assertTrue($this->file->isChanged($compare));
        }
    }

    public function differentMimeTypes(UnitTester $I): void
    {
        foreach ([
            '',
            'json',
            'text/css',
            'text/html',
            'APPLICATION/JSON',
            'application/Json',
        ] as $mimeType) {
            $compare = new File('path', 'public', 1_000, 1_000_000, $mimeType);

            $I->assertTrue($this->file->isChanged($compare));
        }
    }
}
