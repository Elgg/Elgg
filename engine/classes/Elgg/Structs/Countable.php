<?php
namespace Elgg\Structs;

use Countable as NativeCountable;

/**
 * Adds a convenience for checking whether a countable has value 0.
 */
interface Countable extends NativeCountable {
	/**
	 * Indicates whether there are no items in this collection.
	 * 
	 * Although this always returns the same result as count($this) == 0,
	 * it doesn't have to be implemented that way.
	 * 
	 * @return boolean
	 */
	public function isEmpty();
}