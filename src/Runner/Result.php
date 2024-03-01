<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner;

class Result
{
    private function __construct(
        public readonly bool $has_ran,
        public readonly array $errors,
    ) {
    }

    public static function make(): static
    {
        return new static(true, []);
    }

    public static function withErrors(array $errors): static
    {
        if ($errors === []) {
            throw new \Exception('::withErrors() must give at least 1 error');
        }

        return new static(false, $errors);
    }
}
