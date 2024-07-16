<?php

namespace Tests\Unit\Action;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Action\Action;
use TCB\FlysystemSync\Action\Type;
use Tests\Support\UnitTester;

class ActionCest
{
    protected Action $created;
    protected Action $deleted;
    protected Action $updated;
    protected Action $nothing;

    public function _before(): void
    {
        $sourceDiff = new FileAttributes('path', 1);

        $source = new FileAttributes('path');
        $target = new FileAttributes('path');

        $this->created = new Action($source, null);
        $this->deleted = new Action(null, $target);
        $this->updated = new Action($sourceDiff, $target);
        $this->nothing = new Action($source, $target);
    }

    public function pathSetCorrectly(UnitTester $I): void
    {
        $I->assertEquals('path', $this->created->path);
        $I->assertEquals('path', $this->deleted->path);
        $I->assertEquals('path', $this->updated->path);
        $I->assertEquals('path', $this->nothing->path);
    }

    /**
     * Allowing different paths. We will sync to different paths.
     */
    public function canAllowDifferentPaths(UnitTester $I): void
    {
        $source = new FileAttributes('path');
        $target = new FileAttributes('path1');

        $action = new Action($source, $target);

        $I->assertEquals('path', $action->path);
        $I->assertEquals(Type::NOTHING, $action->type);
    }

    public function typeSetCorrectly(UnitTester $I): void
    {
        $I->assertEquals(Type::CREATED, $this->created->type);
        $I->assertEquals(Type::DELETED, $this->deleted->type);
        $I->assertEquals(Type::UPDATED, $this->updated->type);
        $I->assertEquals(Type::NOTHING, $this->nothing->type);
    }

    public function typeExceptionWhenBothAreNull(UnitTester $I): void
    {
        $I->expectThrowable(new \InvalidArgumentException('Both SOURCE and TARGET cannot be NULL.'), function () {
            new Action(null, null);
        });
    }

    public function allowDifferentFileDirectoryTypes(UnitTester $I): void
    {
        $source = new FileAttributes('path');
        $target = new DirectoryAttributes('path');

        $action = new Action($source, $target);

        $I->assertEquals(Type::UPDATED, $action->type, 'Has to be ::UPDATED Type');
    }
}
