<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;
use Throwable;

use function array_key_exists;

class Log
{
    public readonly string $path;

    public readonly string $runner;

    public ?Throwable $exception = null;

    protected array $items = [
        'before' => null,
        'after'  => null,
        'final'  => null,
    ];

    public function __construct(string $path, string $runner)
    {
        $this->path   = PathHelper::prepare($path);
        $this->runner = $runner;
    }

    public function isCorrect(): bool
    {
        return $this->isFinished() && $this->getFinal()['differences'] === [];
    }

    public function getBefore(): array
    {
        return $this->items['before'];
    }

    public function getAfter(): array
    {
        return $this->items['after'];
    }

    public function getFinal(): array
    {
        return $this->items['final'];
    }

    public function isBefore(): bool
    {
        return $this->items['before'] !== null;
    }

    public function isAfter(): bool
    {
        return $this->items['after'] !== null;
    }

    public function isFinished(): bool
    {
        return $this->items['final'] !== null;
    }

    public function before(Path $source, ?Path $target): static
    {
        return $this->add('before', $source, $target);
    }

    public function after(Path $source, ?Path $target): static
    {
        return $this->add('after', $source, $target);
    }

    public function final(Path $source, ?Path $target): static
    {
        return $this->add('final', $source, $target);
    }

    protected function add(string $stage, Path $source, ?Path $target): static
    {
        if ($this->items[$stage] !== null) {
            throw new \Exception('Stage set: ' . $stage);
        }

        $this->items[$stage] = [
            'source'      => $source->toArray(),
            'target'      => $target->toArray(),
            'differences' => $source->getDifferences($target),
        ];

        return $this;
    }
}
