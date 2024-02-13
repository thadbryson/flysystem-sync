Flysystem Sync
==============

About
-----
This is a wrapper for [Flysystem](https://github.com/thephpleague/flysystem) version 2 and 3.
Note that v1.* is a plugin for Flysystem v1.*.

Installation
------------
Use Composer:
```
"thadbryson/flysystem-sync": "^4.0"
```

Supports PHP v7.2 and up.

- It helps you sync 2 directories at a time. There are two directory types.
- Goal: remove source/target terminology
- Goal: sync only specific files too
- Goal: don't write new directories in Util->getWrites(), ->getUpdates()-> or ->getDeletes()
- Goal: multiple save locations with adapters (MountManager)

Source
------
Contents of this directory are moved to Target for writing and updating. If this folder has a path that Target doesn't then the path on Target is deleted.

Target
-----
Source directory. Where things are moved to or deleted from.

How To
======

Here is some example code to set everything up.

```php

use TCB\FlysystemSync\Sync;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter as Adapter;

// Setup file system.
$source = new Filesystem(new Adapter(__DIR__ . '/sync-test/source'));
$target  = new Filesystem(new Adapter(__DIR__ . '/sync-test/target'));

// Create the Sync object. Use root directory. That '/' variable can be any subpath directory.
$sync = new Sync($source, $target, $config = [], $directory = '/');

// Here is how to actually sync things.

// Add all folders and files ON SOURCE and NOT ON TARGET.
$sync->syncWrites();

// Delete all folders and files NOT ON SOURCE and on TARGET.
$sync->syncDeletes();

// Update all folders and files that are on both SOURCE and TARGET.
$sync->syncUpdates();

// This will do ->syncWrites(), ->syncDeletes(), and ->syncUpdates().
$sync->sync();

// And you can get what all these paths are going to be separately.
$paths = $sync->getUtil()->getWrites();  // On Source but not on Target.
$paths = $sync->getUtil()->getDeletes(); // On Target but not on Source.
$paths = $sync->getUtil()->getUpdates(); // On both Source and Target but with different properties.

```

Example Path Array
==================

```
array(2) {
    'create-dir/'      => \League\Flysystem\DirectoryAttributes,
    'create-dir/3.php' => \League\Flysystem\FileAttributes
}
```

That's an example from a var_dump() of what a path will give you.

