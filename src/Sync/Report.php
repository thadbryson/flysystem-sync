<?php

namespace TCB\Flysystem\Sync;

class Report
{
	protected $report = array(
		"visibility" => array(),
    	"create"     => array(),
    	"update"     => array(),
   		"delete"     => array(),
    	"nothing"	 => array()
    );

    /**
     * Call log function.
     *     - Options: visibility(), create(), update(), delete(), or nothing().
     */
    public function __call($name, $args)
    {
    	// If we aren't given a valid log type: throw Exception.
    	// Give usr valid options in message.
    	if (!in_array($name, $this->report)) {
    		$options = implode("', '", array_keys($this->report));

    		throw new \Exception("Invalid function: {$name}. Valid options are: '{$options}'");
    	}

    	// Add to report with name: $args[0] is the path.
    	$this->report[ $name ][] = $args[0];

    	// Return true that it's been logged.
    	return in_array($args[0], $this->report[ $name ]);
    }

    public function clear()
    {
    	foreach (array_keys($this->report) as $key) {
    		$this->report[$key] = array();
    	}

    	return $this;
    }

	public function getReport()
	{
		return $this->report;
	}
}