<?php

namespace TCB\Flysystem\Sync;

use League\Flysystem\FilesystemInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Sync
{
	protected $master = null;
    protected $slaves = null;

    protected $report = array();

    public function __constructor(FilesystemInterface $master, ArrayCollection $slaves)
    {
    	// Set Master file system and slave file systems ArrayCollection().
    	$this->setMaster($master)
    		 ->setSlaves($slaves)
    	;

    	$this->report = array();
    }

    public function run($path)
    {
    	// Master contents - recursive get.
    	$masterpaths = $this->master->listPaths($path, true);

    	// Reset the report on each run.
    	$report = array();

    	// Sync with each Slave.
		foreach ($this->getSlaves()->getIterator() as $key => $slave) {
			$slave = new Slave($slave);

			$report[$key] = $slave->sync($this->master, $path, $masterpaths);
		}

		$this->report = $report;

		return $this;
    }

    public function getReport($key)
    {
    	return $this->report;
    }

   	public function setMaster(FilesystemInterface $master)
   	{
   		$this->master = $master;

   		return $this;
   	}

   	public function getMaster()
   	{
   		return $this->master;
   	}

   	public function setSlaves(ArrayCollection $slaves)
   	{
   		$this->slaves = $slaves;

   		// Return $this for chaining.
   		return $this;
   	}

   	public function getSlaves()
   	{
   		return $this->slaves;
   	}
}