<?php

namespace Elgg\Database;

use Elgg\Cli\Command;
use Elgg\Cli\Progress;
use Elgg\Database\Seeds\Seed;
use Elgg\EventsService;
use Elgg\I18n\Translator;
use Elgg\Invoker;

/**
 * Seeder class
 *
 * Populates the database with rows for testing
 *
 * @internal
 */
class Seeder {

	protected EventsService $events;

	protected Progress $progress;
	
	protected Invoker $invoker;
	
	protected Translator $translator;

	/**
	 * Seeder constructor.
	 *
	 * @param EventsService $events     Events service
	 * @param Progress      $progress   Progress helper
	 * @param Invoker       $invoker    Invoker service
	 * @param Translator    $translator Translator
	 */
	public function __construct(
		EventsService $events,
		Progress $progress,
		Invoker $invoker,
		Translator $translator
	) {
		$this->events = $events;
		$this->progress = $progress;
		$this->invoker = $invoker;
		$this->translator = $translator;
	}

	/**
	 * Load seed scripts
	 *
	 * @param array $options options for seeding
	 *                       - limit: the max number of entities to seed
	 *                       - image_folder: a global (local) image folder to use for image seeding (user/group profile icon, etc)
	 *                       - type: only seed this content type
	 *                       - create: create new entities (default: false)
	 *                       - create_since: lower bound creation time (default: now)
	 *                       - create_until: upper bound creation time (default: now)
	 *
	 * @return void
	 */
	public function seed(array $options = []): void {
		$this->invoker->call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() use ($options) {
			$defaults = [
				'limit' => null,
				'image_folder' => elgg_get_config('seeder_local_image_folder'),
				'type' => '',
				'create' => false,
				'create_since' => 'now',
				'create_until' => 'now',
				'interactive' => true,
				'cli_command' => null,
			];
			$options = array_merge($defaults, $options);

			// set global configuration
			if ($options['image_folder'] !== $defaults['image_folder']) {
				elgg_set_config('seeder_local_image_folder', $options['image_folder']);
			}
			
			unset($options['image_folder']);

			// fetch CLI command
			$cli_command = $options['cli_command'];
			unset($options['cli_command']);

			// interactive mode
			$interactive = $options['interactive'] && empty($options['type']);
			unset($options['interactive']);

			$seeds = $this->getSeederClasses();
			foreach ($seeds as $seed) {
				$seed_options = $options;

				// check for type limitation
				if (!empty($seed_options['type']) && $seed_options['type'] !== $seed::getType()) {
					continue;
				}

				// check the seed limit
				$seed_options['limit'] = $seed_options['limit'] ?? $seed::getDefaultLimit();
				if ($interactive && $cli_command instanceof Command) {
					$seed_options['limit'] = (int) $cli_command->ask($this->translator->translate('cli:database:seed:ask:limit', [$seed::getType()]), $seed_options['limit'], false, false);
				}

				if ($seed_options['limit'] < 1) {
					// skip seeding
					continue;
				}
				
				/* @var $seeder Seed */
				$seeder = new $seed($seed_options);

				$progress_bar = $this->progress->start($seed, $seed_options['limit']);

				$seeder->setProgressBar($progress_bar);

				$seeder->seed();

				$this->progress->finish($progress_bar);
			}
		});
	}

	/**
	 * Remove all seeded entities
	 *
	 * @param array $options unseeding options
	 *                       - type: only unseed this content type
	 *
	 * @return void
	 */
	public function unseed(array $options = []): void {
		$this->invoker->call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() use ($options) {
			$defaults = [
				'type' => '',
			];
			$options = array_merge($defaults, $options);

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
		});
	}
	
	/**
	 * Get the class names of all registered seeders (verified to work for seeding)
	 *
	 * @return string[]
	 */
	public function getSeederClasses(): array {
		$result = [];
		
		$seeds = $this->events->triggerResults('seeds', 'database', [], []);
		foreach ($seeds as $seed) {
			if (!class_exists($seed)) {
				elgg_log("Seeding class {$seed} not found", 'ERROR');
				continue;
			}
			
			if (!is_subclass_of($seed, Seed::class)) {
				elgg_log("Seeding class {$seed} does not extend " . Seed::class, 'ERROR');
				continue;
			}
			
			$result[] = $seed;
		}
		
		return $result;
	}
}
