<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helper;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;

use function array_diff_key;
use function array_filter;
use function array_intersect_key;
use function array_values;

readonly class ActionHelper
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
     * @param FileAttributes[]|DirectoryAttributes[]|null[] $sources
     * @param FileAttributes[]|DirectoryAttributes[]|null[] $targets
     * @throws \Exception
     */
    public function __construct(array $sources, array $targets)
    {
        $sources = $this->filterNonNulls($sources);
        $targets = $this->filterNonNulls($targets);

        $creates = array_diff_key($sources, $targets);
        $deletes = array_diff_key($targets, $sources);
        $updates = $this->filterSames($sources, $targets);

        $this->create_files       = $this->filterFiles($creates);
        $this->create_directories = $this->filterDirectories($creates);

        $this->update_files       = $this->filterFiles($updates);
        $this->update_directories = $this->filterDirectories($updates);

        $this->delete_files       = $this->filterFiles($deletes);
        $this->delete_directories = $this->filterDirectories($deletes);
    }

    protected function filterNonNulls(array $params): array
    {
        return array_filter($params, function (FileAttributes|DirectoryAttributes|null $current): bool {
            // Filter out NULLs.
            return $current !== null;
        });
    }

    protected function filterSames(array $sources, array $targets): array
    {
        return array_filter(
            array_intersect_key($sources, $targets),
            function (FileAttributes|DirectoryAttributes $source) use ($targets) {
                // Must use array_key_exists, value could be NULL and isset() or ??  null wouldn't work.
                $target = $targets[$source->path()] ?? throw new \Exception;

                return FilesystemHelper::isSame($source, $target);
            }
        );
    }

    protected function filterFiles(array $contents): array
    {
        return array_filter(
            $contents,
            fn (FileAttributes|DirectoryAttributes $current) => $current instanceof FileAttributes
        );
    }

    protected function filterDirectories(array $contents): array
    {
        return array_filter(
            $contents,
            fn (FileAttributes|DirectoryAttributes $current) => $current instanceof DirectoryAttributes
        );
    }
}
