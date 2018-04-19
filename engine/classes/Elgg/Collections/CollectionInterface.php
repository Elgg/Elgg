<?php

namespace Elgg\Collections;

/**
 * An interface for organizing items into collections
 */
interface CollectionInterface {

	/**
	 * Returns all collection items by reference
	 *
	 * @return CollectionItemInterface[]
	 */
	public function all();

	/**
	 * Count collection items
	 *
	 * @return int
	 */
	public function count();

	/**
	 * Add a new item to collection
	 *
	 * @param CollectionItemInterface $item Item
	 *
	 * @return static
	 */
	public function add($item);

	/**
	 * Get an item by its ID
	 *
	 * @param string|int $id ID
	 *
	 * @return CollectionItemInterface|null
	 */
	public function get($id);

	/**
	 * Check if collection has an item with a given ID
	 *
	 * @param string|int $id ID
	 *
	 * @return bool
	 */
	public function has($id);

	/**
	 * Remove item from collection by its ID
	 *
	 * @param string|int $id ID
	 *
	 * @return static
	 */
	public function remove($id);

	/**
	 * Replace collection items
	 *
	 * @param CollectionItemInterface[]|Collection $items Items
	 *
	 * @return static
	 */
	public function fill($items);

	/**
	 * Add new items to collection, replacing items with matching IDs
	 *
	 * @param CollectionItemInterface[]|Collection $items Items
	 *
	 * @return static
	 */
	public function merge($items);

	/**
	 * Filter collection items using a custom filter
	 * Returns a new collection instance
	 *
	 * @param callable $callback Filter
	 *
	 * @return static
	 */
	public function filter(callable $callback = null);

	/**
	 * Sort fields using custom callable
	 * If not provided, will sort items by priority
	 *
	 * @param callable $callback Sorter
	 *
	 * @return static
	 */
	public function sort(callable $callback = null);

	/**
	 * Walk through members of the collection and apply a callback
	 *
	 * Unlike CollectionInterface::map(), this method does not return the result of mapping,
	 * rather returns the exact same instance of the collection after walking through
	 * its members by reference
	 *
	 * @see CollectionInterface::map()
	 *
	 * @param callable $callback Callback function
	 * @return static
	 */
	public function walk(callable  $callback);

	/**
	 * Walk through all items in the collection and apply a callback
	 *
	 * @param callable $callback Mapper
	 *
	 * @return mixed
	 */
	public function map(callable $callback);

}