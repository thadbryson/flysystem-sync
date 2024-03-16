<?php

declare(strict_types = 1);

namespace Tests\Support;

use League\Flysystem\Config;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;

use function array_key_exists;
use function str_starts_with;

class TestAdapter implements FilesystemAdapter
{
    public const DEFAULT_CONTENTS = 'CONTENTS';

    public const DEFAULT_VISIBILITY = 'public';

    public const DEFAULT_LAST_MODIFIED = 1;

    public const DEFAULT_FILESIZE = 100;

    public const DEFAULT_MIME_TYPE = 'application/json';

    public array $all = [];

    public function addTestingDirectories(string ...$paths): static
    {
        foreach ($paths as $path) {
            $this->createDirectory($path, new Config);
        }

        return $this;
    }

    public function addTestingFiles(string $path, string $contents = self::DEFAULT_CONTENTS): static
    {
        $this->addFileInternal($path, $contents);

        return $this;
    }

    public function fileExists(string $path): bool
    {
        return
            array_key_exists($path, $this->all) &&
            $this->all[$path]['type'] === StorageAttributes::TYPE_FILE;
    }

    public function directoryExists(string $path): bool
    {
        return
            array_key_exists($path, $this->all) &&
            $this->all[$path]['type'] === StorageAttributes::TYPE_DIRECTORY;
    }

    public function write(string $path, string $contents, Config $config): void
    {
        throw new \Exception('->write() should not be called');
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->addFileInternal($path, (string) $contents);
    }

    protected function addFileInternal(string $path, string $contents): void
    {
        $this->all[$path] = [
            'path'         => $path,
            'type'         => StorageAttributes::TYPE_FILE,
            'contents'     => $contents,
            'visibility'   => static::DEFAULT_VISIBILITY,
            'fileSize'     => static::DEFAULT_FILESIZE,
            'lastModified' => static::DEFAULT_LAST_MODIFIED,
            'mimeType'     => static::DEFAULT_MIME_TYPE,
        ];
    }

    public function read(string $path): string
    {
        throw new \Exception('->write() should not be called');
    }

    public function readStream(string $path)
    {
        return $this->all[$path]['contents'];
    }

    public function delete(string $path): void
    {
        unset($this->all[$path]);
    }

    public function deleteDirectory(string $path): void
    {
        unset($this->all[$path]);
    }

    public function createDirectory(string $path, Config $config): void
    {
        $this->all[$path] = [
            'path'         => $path,
            'type'         => StorageAttributes::TYPE_DIRECTORY,
            'visibility'   => static::DEFAULT_VISIBILITY,
            'lastModified' => static::DEFAULT_LAST_MODIFIED,
        ];
    }

    public function setVisibility(string $path, string $visibility): void
    {
        $this->all[$path]['visibility'] = $visibility;
    }

    protected function getProperty(string $path, string $property): int|string|null
    {
        return $this->all[$path][$property];
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes(
            $path,
            visibility: $this->getProperty($path, 'visibility')
        );
    }

    public function mimeType(string $path): FileAttributes
    {
        return new FileAttributes(
            $path,
            mimeType: $this->getProperty($path, 'mimeType')
        );
    }

    public function lastModified(string $path): FileAttributes
    {
        return new FileAttributes(
            $path,
            lastModified: $this->getProperty($path, 'lastModified')
        );
    }

    public function fileSize(string $path): FileAttributes
    {
        return new FileAttributes($path, $this->getProperty($path, 'fileSize'));
    }

    public function listContents(string $path, bool $deep): iterable
    {
        if ($deep === false) {
            throw new \Exception('->listContents() should only be called DEEP');
        }

        return $this->iterateContents($path);
    }

    private function iterateContents(string $path): iterable
    {
        foreach ($this->all as $content_path => $content) {
            if (str_starts_with($content_path . '/', $path) === false) {
                continue;
            }

            yield $content['type'] === StorageAttributes::TYPE_FILE ?
                new FileAttributes(
                    $content['path'],
                    $content['fileSize'],
                    $content['visibility'],
                    $content['lastModified'],
                    $content['mimeType'],
                ) :

                new DirectoryAttributes(
                    $content['path'],
                    $content['visibility'],
                    $content['lastModified'],
                );
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        throw new \Exception('->move() not allowed');
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        throw new \Exception('->copy() not allowed');
    }
}
