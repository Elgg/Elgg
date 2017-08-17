<?php

namespace Elgg\Database\Seeds;

/**
 * Abstract seed
 *
 * Plugins should extend this class to create their own seeders,
 * add use 'seeds','database' plugin hook to add their seed to the sequence.
 */
abstract class Seed implements Seedable {

	use Seeding;

	/**
	 * Populate database
	 * @return mixed
	 */
	abstract function seed();

	/**
	 * Removed seeded rows from database
	 * @return mixed
	 */
	abstract function unseed();

}
