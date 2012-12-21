<?php

/**
 * Provides common Elgg services.
 *
 * We extend the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned. Extension allows us to keep
 * the container generic.
 *
 * @access private
 *
 * @property-read ElggVolatileMetadataCache $metadataCache
 * @property-read ElggPluginHookService $hooks
 * @property-read ElggDatabase $db
 * @property-read ElggAutoloadManager $autoloadManager
 * @property-read ElggLogger $logger
 */
class Elgg_ServiceProvider extends Elgg_DIContainer {

	/**
	 * @param string $name
	 * @return mixed
	 * @throws RuntimeException
	 */
	public function __get($name) {
		if ($this->has($name)) {
			return $this->get($name);
		}
		throw new RuntimeException("Property '$name' does not exist");
	}

	public function __construct(ElggAutoloadManager $autoload_manager) {

		$this->setValue('autoloadManager', $autoload_manager);
		$this->setValue('hooks', new ElggPluginHookService());

		$this->setFactory('logger', array($this, 'getLogger'));
		$this->setFactory('metadataCache', array($this, 'getMetadataCache'));
		$this->setFactory('db', array($this, 'getDb'));
	}

	protected function getMetadataCache(Elgg_DIContainer $c) {
		return new ElggVolatileMetadataCache();
	}

	protected function getDb(Elgg_DIContainer $c) {
		return new ElggDatabase();
	}

	protected function getLogger(Elgg_DIContainer $c) {
		return new ElggLogger($c->get('hooks'));
	}
}
