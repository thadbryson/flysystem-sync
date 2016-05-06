Flysystem Sync Plugin
=====================

Installation
------------

Use Composer:
```
"thadbryson/flysystem-sync": "@stable"
```

This is a plugin for the Flysystem project. https://github.com/thephpleague/flysystem

It helps you sync 2 directories at a time. There are two types.

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

use TCB\Flysystem\Sync;
use TCB\Flysystem\SyncPlugin;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;

// Setup file system.
$master = new Filesystem(new Adapter(__DIR__ . '/sync-test/master'));
$slave  = new Filesystem(new Adapter(__DIR__ . '/sync-test/slave'));

// Add plugin
$master->addPlugin(new SyncPlugin());

// Get the sync object out. Use root directory. That '/' variable can be any subpath directory.
$sync = $master->getSync($slave, '/');

// You can also just create the Sync object without using a Plugin.
// Again that '/' variable can be any subpath directory.
$sync = new Sync($master, $slave, '/');


Here is how to actually sync things.

```php
// You can do these things separately.
// You may want to do them separately if you add things on Slave that you wouldn't want deleted later.

// Add all folders and files ON MASTER and NOT ON SLAVE.
$sync->syncWrites();

// Delete all folders and files NOT ON MASTER and on SLAVE.
$sync->syncDeletes();

// Update all folders and files that are on both MASTER and SLAVE.
$sync->syncUpdates();

// This will do all these things at once.
$sync->sync();

---

And you can get what all these paths are going to be separately.

```php

$paths = $sync->getWrites();  // array of what paths will be written. On Master but not on Slave.

$paths = $sync->getDeletes(); // array of what paths will be deleted. On Slave but not on Master.

$paths = $sync->getUpdates(); // array of what paths will be updated. On both Master and Slave.

```

Example Path Array
==================

```
array(1) {
    'create-dir/3.php' =>
        array(8) {
            'dirname' => string(10) "create-dir"
            'basename' => string(5) "3.php"
            'extension' => string(3) "php"
            'filename' => string(1) "3"
            'path' => string(16) "create-dir/3.php"
            'type' => string(4) "file"
            'timestamp' => int(1418432987)
            'size' => int(26)
        }
}
```

That's an example from a var_dump() of what a path will give you.

