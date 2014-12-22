<?php
namespace Elgg;

/**
 * Specifies a countable iterator, usually of result rows from a DB
 *
 * @package    Elgg.Core
 * @subpackage DataModel
 * @since      1.11
 */
interface BatchInterface {

	/**
	 * Return the current element
	 *
	 * @see \Iterator::current()
	 * @return mixed
	 */
	public function current();

	/**
	 * Move forward to the next element
	 *
	 * @see \Iterator::next()
	 * @return void
	 */
	public function next();

	/**
	 * Return the key of the current element
	 *
	 * @see \Iterator::key()
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key();

	/**
	 * Is the current position valid?
	 *
	 * @see \Iterator::valid()
	 * @return bool
	 * Returns true on success or false on failure.
	 */
	public function valid();

	/**
	 * Rewind the set to the first element
	 *
	 * @see \Iterator::rewind()
	 * @return void
	 */
	public function rewind();

	/**
	 * Count elements in the set. Note the "set" is a DB query, so this may change at any moment!
	 *
	 * @see \Countable::count()
	 * @return int
	 */
	public function count();
}
