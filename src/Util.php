<?php

declare(strict_types = 1);

namespace TCB\Flysystem;

use Illuminate\Support\Collection;
use League\Flysystem\FilesystemInterface;

class Util
{
    /**
     * Paths to write (on MASTER but NOT SLAVE).
     *
     * @var array
     */
    protected $writes = [];

    /**
     * Paths to delete (on SLAVE but not MASTER).
     *
     * @var array
     */
    protected $deletes = [];

    /**
     * Paths to update (on SLAVE and MASTER but later timestamp on MASTER).
     *
     * @var array
     */
    protected $updates = [];

    /**
     * Sync constructor.
     *
     * @param FilesystemInterface $master
     * @param FilesystemInterface $slave
     * @param string              $dir
     */
    public function __construct(FilesystemInterface $master, FilesystemInterface $slave, $dir = '/')
    {
        $masterPaths = $master->listContents($dir, true);
        $slavePaths  = $slave->listContents($dir, true);

        $masterPaths = Collection::make($masterPaths)->keyBy('path')->sortKeys();
        $slavePaths  = Collection::make($slavePaths)->keyBy('path')->sortKeys();

        $masterPaths->keys()
            ->merge($slavePaths->keys())
            ->map(function (string $path) use ($masterPaths, $slavePaths) {

                return [
                    $path,
                    $masterPaths->has($path),
                    $slavePaths->has($path),
                    $masterPaths->get($path),
                    $slavePaths->get($path)
                ];
            })
            ->eachSpread(function (string $path, bool $isOnMaster, bool $isOnSlave, ?array $master, ?array $slave) {

                // On both: Update if Path files are different somehow.
                if ($isOnMaster && $isOnSlave && static::isDiff($master, $slave)) {

                    $this->updates[$path] = $master;
                }
                // Write: on Master, not Slave
                elseif ($isOnMaster && $isOnSlave === false) {

                    $this->writes[$path] = $master;
                }
                // Delete: not on Master, on Slave
                elseif ($isOnSlave && $isOnMaster === false) {

                    $this->deletes[$path] = $slave;
                }
            });
    }

    /**
     * Should these paths be updated?
     *
     * @param array|null $path1
     * @param array|null $path2
     * @return bool
     */
    public static function isDiff(?array $path1, ?array $path2): bool
    {
        if ($path1 === null || $path2 === null) {
            return false;
        }

        $diffKeys   = array_diff_key($path1, $path2);
        $diffValues = array_diff($path1, $path2);

        return count($diffKeys) > 0 || count($diffValues) > 0;
    }

    /**
     * Get paths to WRITE.
     *
     * @return array
     */
    public function getWrites(): array
    {
        return $this->writes;
    }

    /**
     * Get paths to DELETE.
     *
     * @return array
     */
    public function getDeletes(): array
    {
        return $this->deletes;
    }

    /**
     * Get paths to UPDATES.
     *
     * @return array
     */
    public function getUpdates(): array
    {
        return $this->updates;
    }
}
