<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use TCB\FlysystemSync\Helpers\ActionEnum;
use TCB\FlysystemSync\Paths\Contracts\Path;
use Throwable;

class Log
{
    public const string STAGE_BEFORE = 'before';

    public const string STAGE_AFTER = 'after';

    public const string STAGE_FINAL = 'final';

    public readonly string $source;

    public readonly string $target;

    public readonly string $runner;

    public readonly ActionEnum $action;

    public ?Throwable $exception = null;

    protected array $items = [
        self::STAGE_BEFORE => null,
        self::STAGE_AFTER  => null,
        self::STAGE_FINAL  => null,
    ];

    public function __construct(Path $source, Path $target, string $runner)
    {
        $this->source = $source->path;
        $this->target = $target->path;

        $this->runner = $runner;
        $this->action = ActionEnum::get($this->source, $this->target);

        $this->add(Log::STAGE_BEFORE, $this->source, $this->target);
    }

    public function isCorrect(): bool
    {
        return $this->isFinished() && $this->getFinal()['differences'] === [];
    }

    public function getBefore(): array
    {
        return $this->items[static::STAGE_BEFORE];
    }

    public function getAfter(): array
    {
        return $this->items[static::STAGE_AFTER];
    }

    public function getFinal(): array
    {
        return $this->items[static::STAGE_FINAL];
    }

    public function isBefore(): bool
    {
        return $this->items[static::STAGE_BEFORE] !== null;
    }

    public function isAfter(): bool
    {
        return $this->items[static::STAGE_AFTER] !== null;
    }

    public function isFinished(): bool
    {
        return $this->items[static::STAGE_FINAL] !== null;
    }

    public function add(string $stage, Path $source, ?Path $target): static
    {
        if ($this->items[$stage] !== null) {
            throw new \Exception('Stage set: ' . $stage);
        }

        $this->items[$stage] = [
            'source'      => $source->toArray(),
            'target'      => $target?->toArray(),
            'differences' => $source->getDifferences($target),
        ];

        return $this;
    }
}
