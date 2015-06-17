<?php

namespace Elgg\Structs;

/**
 * Uses a Collection<MapEntry> to implement the Map interface.
 * 
 * This is nicely generic and allows us to use non-strings for keys, but most
 * operations are O(n) in the number of entries, so it's not expected to scale.
 * 
 * @since 2.0.0
 * @access private
 */
class EntryCollectionMap/*<K,V>*/ implements Map/*<K,V>*/ {
	/** @var Collection<MapEntry> */
	private $entries;
	
	/**
	 * Constructor
	 * 
	 * @param Collection<MapEntry> $entries The key-value pairs in this map.
	 */
	public function __construct(Collection/*<MapEntry>*/ $entries) {
		$this->entries = $entries;
	}
	
	/** @inheritDoc */
	public function get(/*K*/ $key) {
		foreach ($this->entries as $entry) {
			if ($entry->key === $key) {
				return $entry->val;
			}
		}
		
		return null;
	}

	/** @inheritDoc */
	public function has(/*K*/ $key) {
		return !is_null($this->get($key));
	}
	
	/** @inheritDoc */
	public function keys() {
		return $this->entries->map(function(MapEntry/*<K,V>*/ $entry) {
			return $entry->key;
		});
	}
	
	/** @inheritDoc */
	public function values() {
		return $this->entries->map(function(MapEntry/*<K,V>*/ $entry) {
			return $entry->val;
		});
	}

	/**
	 * Provides simpler instantiation of this class so you don't need to new up
	 * an ArrayCollection and a bunch of MapEntry instances yourself.
	 * 
	 * For example:
	 * 
	 * ```
	 * $map = EntryCollectionMap::fromArray([
	 *   [$key1, $val1],
	 *   [$key2, $val2],
	 * ]);
	 * ```
	 * 
	 * @param array $entries A list of 
	 * 
	 * @return self
	 */
	public static function fromArray(array $entries) {
		return new self((new ArrayCollection($entries))->map(function($tuple) {
			return new MapEntry($tuple[0], $tuple[1]);
		}));
	}
}