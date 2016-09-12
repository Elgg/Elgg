<?php

namespace Elgg\Mocks\Di;

use ElggGroup;
use ElggObject;
use ElggUser;

/**
 * Mocking service
 *
 * @property-read \Elgg\Mocks\Database                    $db                 Database
 * @property-read \Elgg\Mocks\Database\EntityTable        $entityTable        Entity mocks
 * @property-read \Elgg\Mocks\Database\MetadataTable      $metadataTable      Metadata mocks
 * @property-read \Elgg\Mocks\Database\Annotations        $annotations        Annotation mocks
 * @property-read \Elgg\Mocks\Database\RelationshipsTable $relationshipsTable Annotation mocks
 * @property-read \Elgg\Mocks\Database\SubtypeTable       $subtypeTable       Subtype table mock
 * @property-read \Elgg\Mocks\Database\AccessCollections  $accessCollections  ACL table mock
 *
 * @since 2.3
 */
class MockServiceProvider extends \Elgg\Di\DiContainer {

	/**
	 * Constructor
	 */
	public function __construct() {

		$sp = _elgg_services();

		$this->setValue('session', \ElggSession::getMock());
		
		$this->setFactory('db', function(MockServiceProvider $m) use ($sp) {
			$config = $this->getTestingDatabaseConfig();
			return new \Elgg\Mocks\Database($config, $sp->logger);
		});

		$this->setFactory('entityTable', function(MockServiceProvider $m) use ($sp) {
			return new \Elgg\Mocks\Database\EntityTable(
				$sp->config,
				$m->db,
				$sp->entityCache,
				$sp->metadataCache,
				$m->subtypeTable,
				$sp->events,
				$sp->session,
				$sp->translator,
				$sp->logger
			);
		});

		$this->setFactory('metadataTable', function(MockServiceProvider $m) use ($sp) {
			return new \Elgg\Mocks\Database\MetadataTable($sp->metadataCache, $m->db, $m->entityTable, $sp->events, $m->metastringsTable, $m->session);
		});

		$this->setFactory('annotations', function(MockServiceProvider $m) use ($sp) {
			return new \Elgg\Mocks\Database\Annotations($m->db, $m->session, $sp->events);
		});

		$this->setFactory('metastringsTable', function(MockServiceProvider $m) {
			$pool = new \Elgg\Cache\Pool\InMemory();
			return new \Elgg\Mocks\Database\MetastringsTable($pool, $m->db);
		});

		$this->setFactory('relationshipsTable', function(MockServiceProvider $m) use ($sp) {
			return new \Elgg\Mocks\Database\RelationshipsTable($m->db, $m->entityTable, $m->metadataTable, $sp->events);
		});

		$this->setFactory('subtypeTable', function(MockServiceProvider $m) {
			return new \Elgg\Mocks\Database\SubtypeTable($m->db);
		});

		$this->setFactory('accessCollections', function(MockServiceProvider $m) use ($sp) {
			return new \Elgg\Mocks\Database\AccessCollections(
				$sp->config,
				$m->db,
				$m->entityTable,
				$sp->accessCache,
				$sp->hooks,
				$sp->session,
				$sp->translator
			);
		});

		$this->setFactory('datalist', function(MockServiceProvider $m) use ($sp) {
			$db = $m->db;
			$dbprefix = $db->prefix;
			$pool = new \Elgg\Cache\Pool\InMemory();
			return new \Elgg\Mocks\Database\Datalist($pool, $db, $sp->logger, "{$dbprefix}datalists");
		});
	}

	/**
	 * Setup testing database config
	 *
	 * @return \Elgg\Database\Config
	 */
	public function getTestingDatabaseConfig() {
		$conf = new \stdClass();
		$conf->db['read'][0]['dbhost'] = 0;
		$conf->db['read'][0]['dbuser'] = 'user0';
		$conf->db['read'][0]['dbpass'] = 'xxxx0';
		$conf->db['read'][0]['dbname'] = 'elgg0';
		$conf->db['read'][0]['dbname'] = 'elgg0';
		$conf->db['write'][0]['dbhost'] = 1;
		$conf->db['write'][0]['dbuser'] = 'user1';
		$conf->db['write'][0]['dbpass'] = 'xxxx1';
		$conf->db['write'][0]['dbname'] = 'elgg1';

		$conf->dbprefix = elgg_get_config('dbprefix');

		return new \Elgg\Database\Config($conf);
	}

	/**
	 * Setup a mock user
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggUser
	 */
	public function getUser(array $attributes = array()) {
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_user';
		return $this->entityTable->setup(null, 'user', $subtype, $attributes);
	}

	/**
	 * Setup a mock object
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggObject
	 */
	public function getObject(array $attributes = array()) {
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_object';
		return $this->entityTable->setup(null, 'object', $subtype, $attributes);
	}

	/**
	 * Setup a mock object
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggGroup
	 */
	public function getGroup(array $attributes = array()) {
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_group';
		return $this->entityTable->setup(null, 'group', $subtype, $attributes);
	}

}
