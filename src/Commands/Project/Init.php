<?php

declare(strict_types = 1);

namespace TCB\Flysystem\Commands\Project;

use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use TCB\Flysystem\Commands\AbstractCommand;
use const DIRECTORY_SEPARATOR;

class Init extends AbstractCommand
{
    public const SKELETON_DEFAULT = __DIR__ . '/../../project_skeleton.php';

    protected $signature = 'project:init {name} {saveDirectory} {--skeletonFile}';

    protected $description = 'Make and initialize a new project config file.';

    public function handle(): void
    {
        // Get full filepath to write to.
        $file = $this->assertDirectory() . DIRECTORY_SEPARATOR . $this->assertName() . '.php';

        // Copy the file now.
        $filesystem = new Filesystem;
        $filesystem->copy($this->assertSkeleton(), $file);
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

    private function assertDirectory(): string
    {
        $filesystem = new Filesystem;

        $directory = $this->argument('saveDirectory');
        $directory = trim($directory);
        $directory = rtrim($directory, DIRECTORY_SEPARATOR);

        // Exists?
        if ($filesystem->isDirectory($directory) === false) {
            throw new RuntimeException('Directory not found: ' . $directory);
        }

        // Safe to write to? Not a top tier directory?
        $parts = explode(DIRECTORY_SEPARATOR, $directory);

        if (count($parts) < 1) {
            throw new RuntimeException('Directory is not safe to write to: ' . $directory);
        }

        // Is writable?
        if ($filesystem->isWritable($directory) === false) {
            throw new RuntimeException('Cannot write to directory: ' . $directory);
        }

        return $directory;
    }

    private function assertSkeleton(): string
    {
        $filesystem = new Filesystem;

        $skeleton = $this->option('skeletonFile');

        if ($skeleton === false) {
            $skeleton = static::SKELETON_DEFAULT;
        }

        $skeleton = trim($skeleton);

        if ($filesystem->isFile($skeleton) === false) {
            throw new RuntimeException('Skeleton template file not found: ' . $skeleton);
        }

        if ($filesystem->isReadable($skeleton) === false) {
            throw new RuntimeException('File is not readable: ' . $skeleton);
        }

        return $skeleton;
    }
}