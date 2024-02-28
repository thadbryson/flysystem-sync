<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Filesystem;

use function array_diff_key;
use function array_filter;
use function array_intersect_key;
use function gettype;
use function sprintf;

readonly class Sorter
{
    /**
     * @var FileAttributes[]
     */
    public array $create_files;

    /**
     * @var DirectoryAttributes[]
     */
    public array $create_directories;

    /**
     *
     * @var FileAttributes[]
     */
    public array $delete_files;

    /**
     * @var DirectoryAttributes[]
     */
    public array $delete_directories;

    /**
     * @var FileAttributes[]
     */
    public array $update_files;

    /**
     * @var DirectoryAttributes[]
     */
    public array $update_directories;

    /**
     * @var FileAttributes[]
     */
    public array $nothing_files;

    /**
     * @var DirectoryAttributes[]
     */
    public array $nothing_directories;

    /**
     * @param FileAttributes[]|DirectoryAttributes[]|null[] $sources
     * @param FileAttributes[]|DirectoryAttributes[]|null[] $targets
     * @throws \Exception
     */
    public function __construct(array $sources, array $targets)
    {
        $sources = $this->assertStorageAttributes($sources);
        $targets = $this->assertStorageAttributes($targets);

        $creates = array_diff_key($sources, $targets);          // Has sources, no targets
        $deletes = array_diff_key($targets, $sources);          // Has targets, no sources

        $this->create_files       = $this->filterFiles($creates);
        $this->create_directories = $this->filterDirectories($creates);

        $this->delete_files       = $this->filterFiles($deletes);
        $this->delete_directories = $this->filterDirectories($deletes);

        // Has both targets and sources.
        // Sort through files/directories with and without differences.
        $updates = $this->filterDifferents($sources, $targets);

        $this->update_files       = $this->filterFiles($updates['diffs']);
        $this->update_directories = $this->filterDirectories($updates['diffs']);

        // No action needed.
        // This can be useful for logging.
        $this->nothing_files       = $this->filterFiles($updates['sames']);
        $this->nothing_directories = $this->filterDirectories($updates['sames']);
    }

    protected function assertStorageAttributes(array $array): array
    {
        // Remove NULLs
        $array = array_filter($array, fn (mixed $value): bool => $value !== null);

        // Cannot use array_filter(), need to get the $path key.
        foreach ($array as $path => $value) {
            if ($value instanceof FileAttributes === false &&
                $value instanceof DirectoryAttributes === false
            ) {
                throw new \Exception(sprintf(
                    'Invalid path "%s", must be object %s or %s, found: %s',
                    $path,
                    FileAttributes::class,
                    DirectoryAttributes::class,
                    gettype($value)
                ));
            }

            // Could be a numeric file/directory path.
            // Cast $path
            if ((string) $path !== $value->path()) {
                throw new \Exception(
                    sprintf('Paths do not match for key/value pair, "%s" key, "%s" value', $path, $value->path())
                );
            }
        }

        return $array;
    }

    protected function filterDifferents(array $sources, array $targets): array
    {
        $sames = [];
        $diffs = [];

        foreach (array_intersect_key($sources, $targets) as $source) {
            // Must use array_key_exists, value could be NULL and isset() or ??  null wouldn't work.
            $target = $targets[$source->path()] ?? throw new \Exception;

            Filesystem\Helper::isSame($source, $target) ?
                $sames[$source->path()] = $source :
                $diffs[$source->path()] = $source;
        }

        return [
            'sames' => $sames,
            'diffs' => $diffs,
        ];
    }

    protected function filterFiles(array $contents): array
    {
        return array_filter(
            $contents,
            fn (StorageAttributes $current) => $current instanceof FileAttributes
        );
    }

    protected function filterDirectories(array $contents): array
    {
        return array_filter(
            $contents,
            fn (StorageAttributes $current) => $current instanceof DirectoryAttributes
        );
    }
}
