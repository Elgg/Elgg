<?php

namespace Elgg\Helpers\Collections;

use Elgg\Collections\CollectionItemInterface;

/**
 * @see Elgg\Collections\CollectionsUnitTest
 */
#[\AllowDynamicProperties]
class TestItem implements CollectionItemInterface {
	
	protected string|int $id;
	protected int $priority;
	
	public function __construct($id, $priority) {
		$this->id = $id;
		$this->priority = $priority;
	}
	
	/**
	 * Get unique item identifier within a collection
	 *
	 * @return string|int
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Get priority (weight) of the item within a collection
	 *
	 * @return int
	 */
	public function getPriority() {
		return $this->priority;
	}
}
