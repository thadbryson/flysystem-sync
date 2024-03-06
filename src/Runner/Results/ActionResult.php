<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner\Results;

use TCB\FlysystemSync\Action\Contracts\Action;

readonly class ActionResult
{
    public bool $is_success;

    public function __construct(
        public Action $action,
        public bool $has_executed,
        public array $errors_before_any,
        public array $errors_before,
        public array $errors_after,
        public array $differences_before_any,
        public array $differences_before,
        public array $differences_after,
        public array $differences_after_all
    ) {
        $this->is_success = $has_executed === true &&
                            $this->differences_after_all === [];
    }
}
