<?php

declare(strict_types=1);

namespace TCB\FlysystemSync\Action;

use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;

readonly class Action
{
    public Type $type;

    // @todo handle different SOURCE/TARGET PATHS
    public string $path;
    public bool   $file;

    public function __construct(
        public ?StorageAttributes $source,
        public ?StorageAttributes $target
    )
    {
        if ($this->source === null && $this->target === null) {
            throw new \InvalidArgumentException('Both SOURCE and TARGET cannot be NULL.');
        }

        // ::match() first because it checks SOURCE and TARGET aren't both NULL.
        $this->type = static::getType($source, $target);

        if ($this->type === Type::DELETED) {
            $this->path = $this->target->path();
            $this->file = $this->target->isFile();
        }
        else {
            $this->path = $this->source->path();
            $this->file = $this->source->isFile();
        }
    }

    /**
     * Get the Action\Type: CREATED, etc.
     */
    protected static function getType(?StorageAttributes $source, ?StorageAttributes $target): Type
    {
        $onSource = $source !== null;
        $onTarget = $target !== null;

        $sourceOnly = $onSource /*     */ && $onTarget === false;
        $targetOnly = $onSource === false && $onTarget;

        return match (true) {

            $sourceOnly => Type::CREATED,
            $targetOnly => Type::DELETED,

            // On both
            default     => static::isSame($source, $target) === false ?
                Type::UPDATED :
                Type::NOTHING
        };
    }

    /**
     * Are these the same?
     */
    protected static function isSame(?StorageAttributes $source, ?StorageAttributes $target): bool
    {
        if (
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
