<?php

declare(strict_types=1);

namespace TCB\FlysystemSync;

use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;

enum ActionEnum: string
{
    case CREATE  = 'CREATE';
    case DELETE  = 'DELETE';
    case UPDATE  = 'UPDATE';
    case NOTHING = 'NOTHING';

    public static function get(?StorageAttributes $source, ?StorageAttributes $target): self
    {
        if ($source === null && $target === null) {
            throw new \RuntimeException('PATH not found on either SOURCE or TARGET');
        }

        $onSource = $source !== null;
        $onTarget = $target !== null;

        $sourceOnly = $onSource /*     */ && $onTarget === false;
        $targetOnly = $onSource === false && $onTarget;

        return match (true) {

            $sourceOnly => ActionEnum::CREATE,
            $targetOnly => ActionEnum::DELETE,

            // On both
            default     => self::isSame($source, $target) ?
                ActionEnum::UPDATE :
                ActionEnum::NOTHING
        };
    }

    /**
     *
     */
    public static function isSame(StorageAttributes $source, StorageAttributes $target): bool
    {
        assert($source->path() === $target->path(), 'SOURCE and TARGET paths must be the same to compare.');

        if (
            $source->isDir() /*       */ !== $target->isDir() ||
            $source->isFile() /*      */ !== $target->isFile() ||
            $source->type() /*        */ !== $target->type() ||
            $source->lastModified() /**/ !== $target->lastModified()
        ) {
            return false;
        }

        if (
            $source instanceof FileAttributes &&
            $target instanceof FileAttributes
        ) {
            return $source->fileSize() === $target->fileSize();
        }

        return true;
    }
}
