<?php

declare(strict_types = 1);

namespace TCB\Flysystem\Commands;

use RuntimeException;
use TCB\Flysystem\Files;
use const DIRECTORY_SEPARATOR;

class Init extends AbstractCommand
{
    public const SKELETON_DEFAULT = __DIR__ . '/../../project_skeleton.php';

    protected $signature = 'project:init {name} {saveDirectory} {--skeletonFile}';

    protected $description = 'Make and initialize a new project config file.';

    public function handle(): void
    {
        // Get full filepath to write to.
        $directory = $this->argument('saveDirectory');
        $skeleton  = $this->argument('skeletonFile');

        if ($skeleton === false) {
            $skeleton = static::SKELETON_DEFAULT;
        }

        $files = new Files;

        $skeleton = $files->assertFile($skeleton);
        $path     = $files->path($directory, $this->assertName(), '.php');

        // Copy the file now.
        $files->copy($skeleton, $path);
    }

    private function assertName(): string
    {
        $name = trim($this->argument('name'));
        $name = trim($name);
        $name = trim($name, DIRECTORY_SEPARATOR);

        if ($name === '') {
            throw new RuntimeException('Invalid project name: ' . $name);
        }

        return $name;
    }
}
