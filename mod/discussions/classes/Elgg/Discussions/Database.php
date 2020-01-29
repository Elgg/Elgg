<?php

namespace Elgg\Discussions;

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

		$seeds[] = \Elgg\Discussions\Seeder::class;
	
		return $seeds;
	}
}
