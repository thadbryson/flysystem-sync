<?php

namespace TCB\Flysystem;

use League\Flysystem\FilesystemInterface;

/**
 * Class Util
 *
 * @author Thad Bryson <thadbry@gmail.com>
 */
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
     * @param string $dir
     */
    public function __construct(FilesystemInterface $master, FilesystemInterface $slave, $dir = '/')
    {
        $masterPaths = $this->getPaths($master, $dir);
        $slavePaths  = $this->getPaths($slave, $dir);

        // Get all file paths.
        $allPaths = array_merge(array_keys($masterPaths), array_keys($slavePaths));

        //  Find all WRITE, UPDATE, and DELETE paths.
        foreach ($allPaths as $path) {

            $onMaster = isset($masterPaths[$path]) === true;
            $onSlave  = isset($slavePaths[$path])  === true;

            $master = $onMaster === true ? $masterPaths[$path] : null;
            $slave  = $onSlave  === true ? $slavePaths[$path]  : null;

            // On both: UPDATE?
            if ($onMaster === true && $onSlave === true) {

                // Path files are different somehow.
                if (static::isDiff($master, $slave) === true || static::isNewer($master, $slave) === true) {
                    $this->updates[$path] = $master;
                }
            }
            // Write: on Master, not Slave
            elseif ($onMaster === true && $onSlave === false) {

                // Do not write directories: they get created with files.
                $this->writes[$path] = $master;
            }
            // Delete: not on Master, on Slave
            elseif ($onMaster === false && $onSlave === true) {
                $this->deletes[$path] = $slave;
            }
        }
    }

    /**
     * Should these paths be updated?
     *
     * @param $path1
     * @param $path2
     * @return bool
     */
    public static function isDiff($path1, $path2)
    {
        unset($path1["timestamp"]);
        unset($path2["timestamp"]);
        $diffKeys   = array_diff_key($path1, $path2);
        $diffValues = array_diff($path1, $path2);

        return count($diffKeys) > 0 || count($diffValues) > 0;
    }

    /**
     * @param $master
     * @param $slave
     * @return bool
     */
    public static function isNewer($master, $slave)
    {
        return $master["timestamp"] > $slave["timestamp"];
    }

    /**
     * Get paths to WRITE.
     *
     * @return array
     */
    public function getWrites()
    {
        return $this->writes;
    }

    /**
     * Get paths to DELETE.
     *
     * @return array
     */
    public function getDeletes()
    {
        return $this->deletes;
    }

    /**
     * Get paths to UPDATES.
     *
     * @return array
     */
    public function getUpdates()
    {
        return $this->updates;
    }

    /**
     * Get paths on Filesystem.
     *
     * @param FilesystemInterface $filesystem
     * @param string              $dir        - Dir on filesystem to search.
     * @return array
     */
    protected function getPaths(FilesystemInterface $filesystem, $dir)
    {
        $paths = [];

        foreach ($filesystem->listContents($dir, $recursive = true) as $path) {

            $path['dir'] = $path['type'] === 'dir';

            // Use filepath as key for comparison between MASTER and SLAVE.
            $paths[$path['path']] = $path;
        }

        // Sort by key (filepath).
        ksort($paths);

        return $paths;
    }
}
