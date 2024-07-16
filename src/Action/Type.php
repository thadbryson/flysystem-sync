<?php

declare(strict_types=1);

namespace TCB\FlysystemSync\Action;

/**
 * Action Type, what's done.
 */
enum Type
{
    case CREATED;
    case DELETED;
    case UPDATED;
    case NOTHING;
}
