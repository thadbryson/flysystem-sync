<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Actions\Traits;

trait Update
{
    protected function isExistingReader(): bool
    {
        return true;
    }

    protected function isExistingWriterBefore(): bool
    {
        return true;
    }
}
