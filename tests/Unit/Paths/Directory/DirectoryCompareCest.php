<?php

declare(strict_types = 1);

namespace Tests\Unit\Paths\Directory;

use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;
use Tests\Support\UnitTester;

class DirectoryCompareCest
{
    protected Directory $directory;

    public function _before(): void
    {
        $this->directory = $this->makeBasic();
    }

    protected function makeBasic(): Directory
    {
        return new Directory('path', 'public', 1_000);
    }

    public function whenTargetIsNullTrue(UnitTester $I): void
    {
        $is_changed = $this->directory->isChanged(null);

        $I->assertTrue($is_changed, 'When TARGET is NULL, always CHANGED');
    }

    public function whenTargetTypeIsDifferentTrue(UnitTester $I): void
    {
        $is_changed = $this->directory->isChanged(
            new File('path', 'public', 1_000, 1, 'json')
        );

        $I->assertTrue($is_changed, 'When TARGET is different, always CHANGED');
    }

    public function whenExactlySamePaths(UnitTester $I): void
    {
        $directory1 = $this->makeBasic();
        $directory2 = $this->makeBasic();

        $I->assertFalse($directory1->isChanged($directory2));
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
            $compare = new Directory('path', $visibility, 1_000);

            $I->assertTrue($this->directory->isChanged($compare));
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
            $compare = new Directory('path', 'public', $before_source);

            $I->assertFalse($this->directory->isChanged($compare));
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
            $compare = new Directory('path', 'public', $before_source);

            $I->assertFalse($this->directory->isChanged($compare));
        }
    }
}
