<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

class Runner
{
    protected array $actions = [
        'create_files'        => [],
        'delete_files'        => [],
        'update_files'        => [],
        'nothing_files'       => [],
        'create_directories'  => [],
        'delete_directories'  => [],
        'update_directories'  => [],
        'nothing_directories' => [],
    ];
}
