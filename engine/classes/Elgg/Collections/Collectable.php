<?php

namespace Elgg\Collections;

/**
 * Collectable trait
 */
trait Collectable {

	/**
	 * @var string|int
	 */
	protected $id;

	/**
	 * @var int
	 */
	protected $priority;

	/**
	 * Set collection item id
	 *
	 * @param string|int $id ID
	 *
	 * @return static
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * Get collection item id
	 *
	 * @return int|string
	 */
	public function getId() {
		if (!isset($this->id)) {
			$this->id = base_convert(mt_rand(), 10, 36);
		}

		return $this->id;
	}

	/**
	 * Set priority
	 *
	 * @param int $priority Priority
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}

	/**
	 * Get item priority
	 * @return int
	 */
	public function getPriority() {
		if (!isset($this->priority)) {
			$this->priority = time();
		}

		return $this->priority;
	}

}