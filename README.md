Flysystem Sync Plugin
=====================

About
-----
This is a wrapper for [Flysystem](https://github.com/thephpleague/flysystem) version 2 and up.
Note that v1.* is a plugin for Flysystem v1.*.

Installation
------------
Use Composer:
```
"thadbryson/flysystem-sync": "^2.0"
```

Supports PHP v7.2 and up.

It helps you sync 2 directories at a time. There are two directory types.

Master
------
Contents of this directory are moved to Slave for writing and updating. If this folder has a path that Slave doesn't then the path on Slave is deleted.

Slave
-----
Target directory. Where things are moved to or deleted from.

How To
======

Here is some example code to set everything up.

```php

use TCB\FlysystemSync\Sync;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter as Adapter;

// Setup file system.
$master = new Filesystem(new Adapter(__DIR__ . '/sync-test/master'));
$slave  = new Filesystem(new Adapter(__DIR__ . '/sync-test/slave'));

// Create the Sync object. Use root directory. That '/' variable can be any subpath directory.
$sync = new Sync($master, $slave, $config = [], $directory = '/');

// Here is how to actually sync things.

// Add all folders and files ON MASTER and NOT ON SLAVE.
$sync->syncWrites();

// Delete all folders and files NOT ON MASTER and on SLAVE.
$sync->syncDeletes();

// Update all folders and files that are on both MASTER and SLAVE.
$sync->syncUpdates();

// This will do ->syncWrites(), ->syncDeletes(), and ->syncUpdates().
$sync->sync();

// And you can get what all these paths are going to be separately.

$paths = $sync->getUtil()->getWrites();  // On Master but not on Slave.
$paths = $sync->getUtil()->getDeletes(); // On Slave but not on Master.
$paths = $sync->getUtil()->getUpdates(); // On both Master and Slave but with different properties.

```

Example Path Array
==================

```
array(2) {
    'create-dir/' => \League\Flysystem\DirectoryAttributes,
    'create-dir/3.php' => \League\Flysystem\FileAttributes
}
```

That's an example from a var_dump() of what a path will give you.

