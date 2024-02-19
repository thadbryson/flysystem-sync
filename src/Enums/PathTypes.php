<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Enums;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;
use function sprintf;

enum PathTypes: string
{
    case FILE         = 'file';
    case DIRECTORY    = 'string';
    case NON_EXISTING = 'null';

    public static function match(mixed $var): ?self
    {
        if ($var === null) {
            return self::NON_EXISTING;
        }
        if ($var instanceof FileAttributes) {
            return self::FILE;
        }
        elseif ($var instanceof DirectoryAttributes) {
            return self::DIRECTORY;
        }

        return null;
    }

    public static function assert(self $type, ?StorageAttributes $var): ?StorageAttributes
    {
        $given = self::match($var);

        if ($type !== $given) {
            throw new \Exception(sprintf('Invalid PATH. Expected "%s"',
                $type->value
            ));
        }

        return $var;
    }

    public static function assertPath(?StorageAttributes $var): StorageAttributes
    {
        if (self::match($var) === null) {
            throw new \Exception('Not a PATH');
        }

        return $var;
    }

    public static function assertDirectory(?StorageAttributes $var): DirectoryAttributes
    {
        /** @var DirectoryAttributes $found */
        $found = self::assert(self::DIRECTORY, $var);

        return $found;
    }

    public static function assertFile(?StorageAttributes $var): FileAttributes
    {
        /** @var FileAttributes $found */
        $found = self::assert(self::FILE, $var);

        return $found;
    }

    public static function assertAll(self $type, array $all): array
    {
        foreach ($all as $current) {
            PathTypes::assert($type, $current);
        }

        return $all;
    }

    public static function assertPathAll(array $all): array
    {
        foreach ($all as $current) {
            PathTypes::assertPath($current);
        }

        return $all;
    }

    public static function assertDirectoryAll(array $all): array
    {
        foreach ($all as $current) {
            PathTypes::assertDirectory($current);
        }

        return $all;
    }

    public static function assertFileAll(array $all): array
    {
        foreach ($all as $current) {
            PathTypes::assertFile($current);
        }

        return $all;
    }
}
