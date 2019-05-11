<?php

declare(strict_types = 1);

namespace TCB\Flysystem;

use Illuminate\Filesystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use RuntimeException;
use function implode;
use function realpath;
use const DIRECTORY_SEPARATOR;

class Files extends Filesystem
{
    public function path(string ...$parts): string
    {
        foreach ($parts as $key => $part) {

            $parts[$key] = trim($part, DIRECTORY_SEPARATOR . ' ');
        }

        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    public function assertDirectory(string $directory): string
    {
        $directory = trim($directory);
        $directory = rtrim($directory, DIRECTORY_SEPARATOR);
        $directory = realpath($directory);

        // Safe to write to? Not a top tier directory?
        $parts = explode(DIRECTORY_SEPARATOR, $directory);

        if (count($parts) < 1) {
            throw new RuntimeException('Directory is not safe to write to: ' . $directory);
        }

        // Exists?
        if ($this->isDirectory($directory) === false) {

            $this->makeDirectory($directory, 0755, true);

            if ($this->isDirectory($directory) === false) {
                throw new RuntimeException('Directory does not exist: ' . $directory);
            }
        }

        return $directory;
    }

    public function assertFile(string $file): string
    {
        $file = trim($file);
        $file = realpath($file);

        if ($file === false) {
            throw new RuntimeException('File not found: ' . $file);
        }

        if ($this->isReadable($file) === false) {
            throw new RuntimeException('File is not readable: ' . $file);
        }

        return $file;
    }

    public function getConfig(string $filepath): array
    {
        $project = $this->assertFile($filepath);
        $project = $this->getRequire($project);

        /** @var array $project */
        if (is_array($project) === false) {
            throw new RuntimeException('Invalid project configuration. File must return an array.');
        }

        $sources = $this->prepareAdapters($project['sources'] ?? null, 'sources');
        $backups = $this->prepareAdapters($project['backups'] ?? null, 'backups');

        return [$sources, $backups];
    }

    /**
     * @param string[]|AdapterInterface[] $directories
     * @param string                      $type
     * @return AdapterInterface[]
     */
    private function prepareAdapters($directories, string $type): array
    {
        if (is_array($directories) === false) {
            throw new RuntimeException(sprintf('Invalid config "%s". It must be an array.', $type));
        }

        if ($directories === []) {
            throw new RuntimeException(sprintf('Invalid config "%s". Cannot be an empty array.', $type));
        }

        $files = new Files;

        foreach ($directories as $index => $dest) {

            if ($dest instanceof AdapterInterface) {
                continue;
            }

            if (is_string($dest) === false) {
                throw new RuntimeException(sprintf('Invalid config "%s". Config must be a string or %s object. At index: %s',
                    $type, AdapterInterface::class, $index));
            }

            // Validate that it's an existing stsring.
            $dest = $files->assertDirectory($dest);

            // Create Local Adapter object.
            $directories[$index] = new Local($dest);
        }

        return $directories;
    }
}