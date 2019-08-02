<?php

namespace Elgg\Structs;

use Countable;
use Iterator;

/**
 * A read-only interface to a (possibly mutable) group of items.
 *
 * Read-only provides some nice guarantees that can be harnessed for things
 * like caching, lazy evaluation, respecting HTTP semantics of GET/HEAD, etc.
 *
 * We do not extend ArrayAccess, because:
 *  * Collections aren't writable by default
 *  * Collections don't have a defined order by default
 *  * Collections aren't all Maps by default ;)
 *
 * Extensions may provide one or more of these features.
 *
 * TODO(ewinslow): If PHP had generics support, we'd add that here.
 *
 * DO NOT EXTEND OR IMPLEMENT this interface outside of this package.
 * Doing so would cause additions to the API to be breaking changes, which is
 * not what we want. You have a couple of options for how to proceed:
 *  * File a feature request
 *  * Submit a PR
 *  * Use composition -- http://en.wikipedia.org/wiki/Composition_over_inheritance
 *
 * @since 1.10
 * @internal
 */
interface Collection extends Countable, Iterator {
	
	/**
	 * Returns a new collection only containing the elements which pass the filter.
	 *
	 * @param callable $filter Receives an item. Return true to keep the item.
	 *
	 * @return Collection
	 */
	public function filter(callable $filter);
	
	/**
	 * Returns true iff the item is in this collection at least once.
	 *
	 * @param mixed $item The object or value to check for
	 *
	 * @return boolean
	 */
	public function contains($item);

	/**
	 * Take items of the collection and return a new collection
	 * with all the items having the $mapper applied to them.
	 *
	 * The callable is not guaranteed to execute immediately for each item.
	 *
	 * @param callable $mapper Returns the mapped value
	 *
	 * @return Collection
	 */
	public function map(callable $mapper);
}
