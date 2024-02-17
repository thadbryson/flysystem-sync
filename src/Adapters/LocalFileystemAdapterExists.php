<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Adapters;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\VisibilityConverter;
use League\MimeTypeDetection\MimeTypeDetector;
use function is_dir;
use const LOCK_EX;

/**
 * I'm setting $lazyRootCreation to TRUE here.
 * Sometimes I don't want it automatically creating directories.
 *
 * @author Thad Bryson
 */
class LocalFileystemAdapterExists extends LocalFilesystemAdapter
{
    public function __construct(
        string $location,
        VisibilityConverter $visibility = null,
        int $writeFlags = LOCK_EX,
        int $linkHandling = self::DISALLOW_LINKS,
        MimeTypeDetector $mimeTypeDetector = null,
        bool $useInconclusiveMimeTypeFallback = false,
    ) {
        parent::__construct(
            $location,
            $visibility,
            $writeFlags,
            $linkHandling,
            $mimeTypeDetector,
            false,  // $lazyRootCreation
            $useInconclusiveMimeTypeFallback,
        );

        if (is_dir($location) === false) {
            throw new \Exception('Filesystem location does not exist: ' . $location);
        }
    }

    protected function ensureRootDirectoryExists(): void
    {
        // Do nothing
    }
}
