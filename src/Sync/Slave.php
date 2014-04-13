<?php

namespace TCB\Flysystem\Sync;

use League\Flysystem\FilesystemInterface;

class Slave
{
	protected $slave = null;
	protected $paths = null;

	protected $master = null;
	protected $path   = null;

	protected $report = null;

	public function __construct(FilesystemInterface $slave)
	{
		$this->slave = $slave;

		// Initialize the Sync report.
    	$this->report = new Report();
	}

	public function sync(FilesystemInterface $master, $path, $masterpaths = null)
    {
    	// Get ALL paths on slave. Search recursively.
    	$this->path = $path;

    	$this->master = $master;

    	if ($masterpaths === null) {
    		$masterpaths = $this->master->listPaths($this->path, true);
    	}

    	$this->clearReport();

    	$this->createDiffs($masterpaths)	// Create paths on Slave.
    		 ->deleteDiffs($masterpaths)	// Delete paths on Slave.
    		 ->updateDiffs($masterpaths)	// Check common paths on Slave.
    	;

    	return $this->report;
    }

    public function getPaths()
    {
    	// Init paths if it's not set.
    	if ($this->paths === null) {
    		$this->paths = $this->slave->listPaths($this->path, true);
    	}

    	// Return paths.
    	return $this->paths;
    }

    public function getDiff($givenpaths, $onslave = false)
    {
    	return ($onslave) ?
    		array_diff($this->getPaths(), $givenpaths) :   // On MASTER but not SLAVE
    		array_diff($givenpaths, $this->getPaths())     // On SLAVE but not MASTER
    	;
    }

    public function getCommon($givenpaths)
    {
    	return array_intersect($givenpaths, $this->getPaths());
    }

    public function createDiffs(array $givenpaths)
    {
    	// Get paths on MASTER not on SLAVE: create.
    	foreach ($this->getDiff($givenpaths, false) as $path) {
    		$this->slave->create($path, $this->master->read($path));
    		$this->report->create($path);
    	}

    	return $this;
    }

    public function deleteDiffs(array $givenpaths)
    {
    	// Get paths on SLAVE not on MASTER: delete.
    	foreach ($this->getDiff($givenpaths, true) as $path) {
    		$this->slave->delete($path);
    		$this->report->delete($path);
    	}

    	return $this;
    }

    public function updateDiffs(array $givenpaths)
    {
    	foreach ($this->getCommon($givenpaths) as $path) {
    		$hit = false;

    		// If the visibility is different: change it.
    		if ($this->master->getVisibility($path) != $this->slave->getVisibility($path)) {
    			$this->slave->setVisibility($path, $this->master->getVisibility($path));
				$hit = $this->report->visibility($path);
    		}

    		// If the timestamp, size, or mimetypes are different: update the file.
    		if (($this->master->getTimestamp($path) >  $this->->getTimestamp($path) ||
		    	 $this->master->getSize($path)      != $this->->getSize($path)      ||
		    	 $this->master->getMimetype($path)  != $this->->getMimetype($path)
		    )) {
    			$this->slave->update($path, $this->master->read($path));
    			$hit = $this->report->update($path);
    		}

    		// If the path hasn't been hit: log "nothing" to report.
    		if (!$hit) {
    			$hit = $this->report->nothing($path);
    		}
    	}

    	return $this;
    }

    public function clearReport()
    {
    	$this->report->clear();

    	return $this;
    }
}