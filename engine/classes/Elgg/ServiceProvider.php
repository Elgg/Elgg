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
 * @property-read Elgg_ActionsService $actions
 * @property-read ElggPluginHookService $hooks
 * @property-read ElggEventService $events
 * @property-read ElggViewService $views
 * @property-read ElggAutoP $autoP
 * @property-read ElggDatabase $db
 * @property-read ElggAutoloadManager $autoloadManager
 * @property-read ElggLogger $logger
 * @property-read Elgg_AmdConfig $amdConfig
 * @property-read ElggSession $session
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
		$this->setValue('actions', new Elgg_ActionsService());
		$this->setValue('hooks', new ElggPluginHookService());
		$this->setValue('events', new ElggEventService());

		$this->setFactory('views', array($this, 'getViews'));
		$this->setFactory('autoP', array($this, 'getAutoP'));
		$this->setFactory('logger', array($this, 'getLogger'));
		$this->setFactory('metadataCache', array($this, 'getMetadataCache'));
		$this->setFactory('db', array($this, 'getDb'));
		$this->setFactory('amdConfig', array($this, 'getAmdConfig'));
		$this->setFactory('session', array($this, 'getSession'));
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

	protected function getViews(Elgg_DIContainer $c) {
		return new ElggViewService($c->hooks, $c->logger);
	}

	protected function getAutoP(Elgg_DIContainer $c) {
		return new ElggAutoP();
	}

	protected function getAmdConfig(Elgg_DIContainer $c) {
		$obj = new Elgg_AmdConfig();
		$obj->setBaseUrl(_elgg_get_simplecache_root() . "js/");
		return $obj;
	}

	protected function getSession(Elgg_DIContainer $c) {
		$handler = new Elgg_Http_DatabaseSessionHandler($c->db);
		$storage = new Elgg_Http_NativeSessionStorage($handler);
		$session = new ElggSession($storage);
		$session->setName('Elgg');
		return $session;
	}
}
