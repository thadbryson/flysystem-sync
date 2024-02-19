<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Traits;

use League\Flysystem\FilesystemOperator;
use TCB\FlysystemSync\Filesystems\Reader;

/**
 * @const array ASSERT
 */
trait ActionTrait
{
    protected readonly Reader $reader;

    protected readonly FilesystemOperator $writer;

    protected ?string $source;

    protected ?string $target;

    public function __construct(
        Reader $reader,
        FilesystemOperator $writer,
        ?string $source,
        ?string $target
    ) {
        $source_type = static::ASSERT['source'] ?? null;
        $target_type = static::ASSERT['target'] ?? null;

        if ($source_type !== null) {
            $reader->assertType($source_type, $source);
        }

        if ($target_type !== null) {
            (new Reader($writer))->assertType($target_type, $target);
        }

        $this->reader = $reader;
        $this->writer = $writer;

        $this->source = $source;
        $this->target = $target;
    }

    abstract public function execute(): void;
}
