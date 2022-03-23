<?php

namespace Elgg\Di;

use DI\Container;
use DI\ContainerBuilder;

/**
 * Base DI Container class
 *
 * @internal
 */
abstract class DiContainer extends Container {

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		$service = $this->get($name);
		
		// get the traits implemented directly by the service
		$traits = class_uses($service, true);
		
		// check for certain global cases
		if (in_array(\Elgg\Traits\Debug\Profilable::class, $traits) && !$service->hasTimer()) {
			// profiling is supported
			if ($service instanceof \Elgg\Database) {
				// the database uses a different config flag to enable profiling
				if ($this->config->profiling_sql) {
					// need to get the timer from the InternalContainer
					// especially if the current DiContainer is the PublicContainer
					$service->setTimer(_elgg_services()->timer);
				}
			} elseif ($this->config->enable_profiling) {
				// need to get the timer from the InternalContainer
				// especially if the current DiContainer is the PublicContainer
				$service->setTimer(_elgg_services()->timer);
			}
		}
		
		return $service;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		// prevent setting of class variables using container->service
		$this->set($name, $value);
	}

	/**
	 * Unsets the service to force rebuild on next request
	 *
	 * @param string $name
	 *
	 * @return void
	 */
	public function reset(string $name): void {
		unset($this->resolvedEntries[$name]);
	}

	/**
	 * @param array $options additional options
	 *
	 * @return self
	 */
	public static function factory(array $options = []) {
		$dic_builder = new ContainerBuilder(static::class);
		$dic_builder->useAnnotations(false);
		
		foreach (static::getDefinitionSources() as $location) {
			$dic_builder->addDefinitions($location);
		}
		
		return $dic_builder->build();
	}

	/**
	 * Returns an array of file locations
	 *
	 * @return string[]
	 */
	abstract public static function getDefinitionSources(): array;
}
