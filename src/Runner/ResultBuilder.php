<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner;

use TCB\FlysystemSync\Action\Contracts\Action;

class ResultBuilder
{
    protected readonly Action $action;

    /**
     * Has the Action been executed ->execute() ?
     */
    protected bool $has_executed = false;

    /**
     * Any errors BEFORE any executions
     */
    protected array $errors_before_any = [];

    /**
     * Any errors BEFORE execution
     */
    protected array $errors_before = [];

    /**
     * Any errors AFTER execution
     */
    protected array $errors_after = [];

    /**
     * PATH differences BEFORE any executions
     */
    protected array $differences_before_any = [];

    /**
     * PATH differences BEFORE exection
     */
    protected array $differences_before = [];

    /**
     * PATH differences AFTER execution
     */
    protected array $differences_after = [];

    public function __construct(Action $action)
    {
        $this->action = $action;

        $this->differences_before_any = $action->getDifferences();
        $this->errors_before_any      = $this->getErrors();
    }

    public function execute(): static
    {
        $this->differences_before = $this->action->getDifferences();
        $this->errors_before      = $this->getErrors();

        // Has not execute and has 0 errors
        if ($this->has_executed === false && $this->errors_before_any === []) {
            $this->action->execute();

            $this->has_executed = true;
        }

        $this->errors_after      = $this->getErrors();
        $this->differences_after = $this->action->getDifferences();

        return $this;
    }

    public function finalize(): Result
    {
        return new Result(
            $this->action,
            $this->has_executed,
            $this->errors_before_any,
            $this->errors_before,
            $this->errors_after,
            $this->differences_before_any,
            $this->differences_before,
            $this->differences_after,
            $this->action->getDifferences()
        );
    }

    protected function getErrors(): array
    {
        $errors = [];

        if ($this->action->isReaderExistingValid() === false) {
            $errors[] = $this->action->isOnReader() ?
                ":type must exist on the READER. It does not" :
                ":type cannot exist on the READER";
        }

        if ($this->action->isWriterExistingValid() === false) {
            $errors[] = $this->action->isOnWriter() ?
                ":type must exist on the WRITER. It does not" :
                ":type cannot exist on the WRITER";
        }

        return $errors;
    }
}
