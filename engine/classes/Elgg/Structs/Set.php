<?php
namespace Elgg\Structs;

/**
 * A collection that only contains each item once.
 *
 * @access private
 */
interface Set extends Collection {
	/**
	 * @return Set<T>
	 * @inheritDoc
	 */
	public function filter(callable $filter);
}
