<?php

namespace Elgg\Database;

use Elgg\Database\Seeds\Objects;
use Elgg\Database\Seeds\Groups;
use Elgg\Database\Seeds\Seed;
use Elgg\Database\Seeds\Users;
use Elgg\PluginHooksService;

/**
 * Seeder class
 *
 * Populates the database with rows for testing
 *
 * @access private
 */
class Seeder {

	private $seeds = [
		Users::class,
		Groups::class,
		Objects::class,
	];

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * Seeder constructor.
	 *
	 * @param PluginHooksService $hooks Hooks registration service
	 */
	public function __construct(PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}

	/**
	 * Load seed scripts
	 *
	 * @return void
	 */
	public function seed() {
		$ia = elgg_set_ignore_access(true);

		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		
		foreach ($this->seeds as $seed) {
			if (!class_exists($seed)) {
				continue;
			}
			$seed = new $seed();
			if (!is_subclass_of($seed, Seed::class)) {
				continue;
			}

			$seed->seed();
		}

		access_show_hidden_entities($ha);

		elgg_set_ignore_access($ia);
	}

	/**
	 * Remove all seeded entities
	 * @return void
	 */
	public function unseed() {

		$ia = elgg_set_ignore_access(true);

		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		foreach ($this->seeds as $seed) {
			if (!class_exists($seed)) {
				continue;
			}
			$seed = new $seed();
			if (!is_subclass_of($seed, Seed::class)) {
				continue;
			}

			$seed->unseed();
		}

		access_show_hidden_entities($ha);

		elgg_set_ignore_access($ia);
	}
}