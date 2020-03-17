<?php

namespace Elgg\Database\Seeds;

use Elgg\Cli\Progressing;

/**
 * Abstract seed
 *
 * Plugins should extend this class to create their own seeders,
 * add use 'seeds','database' plugin hook to add their seed to the sequence.
 */
abstract class Seed implements Seedable {

	use Seeding;
	use Progressing;

	/**
	 * Seed constructor.
	 *
	 * @param int $limit Number of item to seed
	 */
	public function __construct($limit = null) {
		if (isset($limit)) {
			$this->limit = $limit;
		}
	}
	
	/**
	 * Register this class for seeding
	 *
	 * @param \Elgg\Hook $hook 'seeds', 'database'
	 *
	 * @return array
	 */
	final public static function register(\Elgg\Hook $hook) {
		$seeds = $hook->getValue();
		
		$seeds[] = static::class;
		
		return $seeds;
	}

	/**
	 * Populate database
	 *
	 * @return mixed
	 */
	abstract public function seed();

	/**
	 * Removed seeded rows from database
	 *
	 * @return mixed
	 */
	abstract public function unseed();

}
