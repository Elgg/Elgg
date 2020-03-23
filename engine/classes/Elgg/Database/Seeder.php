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
 * @internal
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
	 * @param array $options options for seeding
	 *                       - limit: the max number of entities to seed
	 *                       - image_folder: a global (local) image folder to use for image seeding (user/group profile icon, etc)
	 *                       - type: only seed this content type
	 *                       - create: create new entities (default: false)
	 *
	 * @return void
	 */
	public function seed(array $options = []) {
		$defaults = [
			'limit' => max(elgg_get_config('default_limit'), 20),
			'image_folder' => elgg_get_config('seeder_local_image_folder'),
			'type' => '',
			'create' => false,
		];
		$options = array_merge($defaults, $options);

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$ha = _elgg_services()->session->getDisabledEntityVisibility();
		_elgg_services()->session->setDisabledEntityVisibility(true);

		$seeds = $this->getSeederClasses();

		// set global configuration
		if ($options['image_folder'] !== $defaults['image_folder']) {
			elgg_set_config('seeder_local_image_folder', $options['image_folder']);
		}

		foreach ($seeds as $seed) {
			// check for type limitation
			if (!empty($options['type']) && $options['type'] !== $seed::getType()) {
				continue;
			}

			/* @var $seeder Seed */
			$seeder = new $seed([
				'limit' => $options['limit'],
				'create' => $options['create'],
			]);

			$progress_bar = $this->progress->start($seed, $options['limit']);

			$seeder->setProgressBar($progress_bar);

			$seeder->seed();

			$this->progress->finish($progress_bar);
		}

		_elgg_services()->session->setDisabledEntityVisibility($ha);
		_elgg_services()->session->setIgnoreAccess($ia);
	}

	/**
	 * Remove all seeded entities
	 *
	 * @param array $options unseeding options
	 *                       - type: only unseed this content type
	 *
	 * @return void
	 */
	public function unseed(array $options = []) {
		$defaults = [
			'type' => '',
		];
		$options = array_merge($defaults, $options);

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$ha = _elgg_services()->session->getDisabledEntityVisibility();
		_elgg_services()->session->setDisabledEntityVisibility(true);

		$seeds = $this->getSeederClasses();

		foreach ($seeds as $seed) {
			// check for type limitation
			if (!empty($options['type']) && $options['type'] !== $seed::getType()) {
				continue;
			}

			/* @var $seeder Seed */
			$seeder = new $seed();

			$progress_bar = $this->progress->start($seed, $seeder->getCount());

			$seeder->setProgressBar($progress_bar);

			$seeder->unseed();

			$this->progress->finish($progress_bar);
		}

		_elgg_services()->session->setDisabledEntityVisibility($ha);
		_elgg_services()->session->setIgnoreAccess($ia);
	}
	
	/**
	 * Get the class names of all registered seeders (verified to work for seeding)
	 *
	 * @return string[]
	 */
	public function getSeederClasses() {
		$result = [];
		
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
			
			$result[] = $seed;
		}
		
		return $result;
	}
}
