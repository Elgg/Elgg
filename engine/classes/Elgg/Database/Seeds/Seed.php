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
	 * @var int Max number of items to be created by the seed
	 */
	protected $limit = 20;

	/**
	 * @var bool Create new entities
	 */
	protected $create = false;
	
	/**
	 * Seed constructor.
	 *
	 * @param array $options seeding options
	 *                       - limit: Number of item to seed
	 *                       - create: create new entities (default: false)
	 */
	public function __construct(array $options = []) {
		$limit = (int) elgg_extract('limit', $options);
		if ($limit > 0) {
			$this->limit = $limit;
		}
		
		$this->create = (bool) elgg_extract('create', $options, $this->create);
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

	/**
	 * Get the (un)seeding type of this handler
	 *
	 * @return string
	 */
	abstract public static function getType() : string;
}
