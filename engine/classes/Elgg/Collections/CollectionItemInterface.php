<?php

namespace Elgg\Collections;

/**
 * Collection item interface
 */
interface CollectionItemInterface {

	/**
	 * Get unique item identifier within a collection
	 * @return string|int
	 */
	public function getId();

	/**
	 * Get priority (weight) of the item within a collection
	 * @return int
	 */
	public function getPriority();
}