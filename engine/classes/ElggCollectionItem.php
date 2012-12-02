<?php

/**
 * Value object for handling collection item
 *
 * @access private
 */
class ElggCollectionItem {
	protected $priority;
	protected $value;
	protected $time;

	/**
	 * @param int $value
	 * @param int $priority
	 * @param int $time
	 */
	public function __construct($value, $priority = null, $time = null) {
		if (!$time) {
			$time = time();
		}
		$this->priority = $priority;
		$this->value = $value;
		$this->time = $time;
	}

	/**
	 * @return int
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return int|null
	 */
	public function getPriority()
	{
		return $this->priority;
	}

	/**
	 * @return int
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * @param int $priority
	 */
	public function setPriority($priority)
	{
		$this->priority = $priority;
	}
}
