<?php

namespace Elgg\File;

/**
 * Hook callbacks for database
 *
 * @since 4.0
 *
 * @internal
 */
class Database {

	/**
	 * Register entities for database seeding
	 *
	 * @param \Elgg\Hook $hook 'seeds', 'database'
	 *
	 * @return array
	 */
	public static function registerSeeds(\Elgg\Hook $hook) {
		$seeds = $hook->getValue();

		$seeds[] = \Elgg\File\Seeder::class;
	
		return $seeds;
	}
}
