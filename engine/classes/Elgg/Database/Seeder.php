<?php

namespace Elgg\Database;

use Elgg\Cli\Progress;
use Elgg\Database\Seeds\Seed;
use Elgg\PluginHooksService;

/**
 * Seeder class
 *
 * Populates the database with rows for testing
 *
 * @access private
 */
class Seeder {

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var Progress
	 */
	protected $progress;

	/**
	 * Seeder constructor.
	 *
	 * @param PluginHooksService $hooks    Hooks registration service
	 * @param Progress           $progress Progress helper
	 */
	public function __construct(
		PluginHooksService $hooks,
		Progress $progress
	) {
		$this->hooks = $hooks;
		$this->progress = $progress;
	}

	/**
	 * Load seed scripts
	 *
	 * @param int $limit the max number of entities to seed
	 *
	 * @return void
	 */
	public function seed($limit = null) {
		if (!$limit) {
			$limit = max(elgg_get_config('default_limit'), 20);
		}

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$ha = _elgg_services()->session->getDisabledEntityVisibility();
		_elgg_services()->session->setDisabledEntityVisibility(true);

		$seeds = $this->hooks->trigger('seeds', 'database', null, []);

		foreach ($seeds as $seed) {
			if (!class_exists($seed)) {
				elgg_log("Seeding class $seed not found", 'ERROR');
				continue;
			}
			if (!is_subclass_of($seed, Seed::class)) {
				elgg_log("Seeding class $seed does not extend " . Seed::class, 'ERROR');
				continue;
			}

			$seeder = new $seed($limit);
			/* @var $seeder Seed */

			$progress_bar = $this->progress->start($seed, $limit);

			$seeder->setProgressBar($progress_bar);

			$seeder->seed();

			$this->progress->finish($progress_bar);
		}

		_elgg_services()->session->setDisabledEntityVisibility($ha);
		_elgg_services()->session->setIgnoreAccess($ia);
	}

	/**
	 * Remove all seeded entities
	 * @return void
	 */
	public function unseed() {

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$ha = _elgg_services()->session->getDisabledEntityVisibility();
		_elgg_services()->session->setDisabledEntityVisibility(true);

		$seeds = $this->hooks->trigger('seeds', 'database', null, []);

		foreach ($seeds as $seed) {
			if (!class_exists($seed)) {
				elgg_log("Seeding class $seed not found", 'ERROR');
				continue;
			}
			if (!is_subclass_of($seed, Seed::class)) {
				elgg_log("Seeding class $seed does not extend " . Seed::class, 'ERROR');
				continue;
			}
			$seeder = new $seed();
			/* @var $seeder Seed */

			$progress_bar = $this->progress->start($seed);

			$seeder->setProgressBar($progress_bar);

			$seeder->unseed();

			$this->progress->finish($progress_bar);
		}

		_elgg_services()->session->setDisabledEntityVisibility($ha);
		_elgg_services()->session->setIgnoreAccess($ia);
	}
}