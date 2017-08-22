<?php

namespace Elgg\Mocks\Di;

/**
 * Mocking service
 *
 * @property-read \Elgg\Mocks\Database                      $db                 Database
 * @property-read \Elgg\Mocks\Database\EntityTable          $entityTable        Entity mocks
 * @property-read \Elgg\Mocks\Database\MetadataTable        $metadataTable      Metadata mocks
 * @property-read \Elgg\Mocks\Database\Annotations          $annotations        Annotation mocks
 * @property-read \Elgg\Mocks\Database\RelationshipsTable   $relationshipsTable Annotation mocks
 * @property-read \Elgg\Mocks\Database\SubtypeTable         $subtypeTable       Subtype table mock
 * @property-read \Elgg\Mocks\Database\AccessCollections    $accessCollections  ACL table mock
 * @property-read \Elgg\Mocks\Database\PrivateSettingsTable $privateSettings    Private settings table mock
 *
 * @since 2.3
 */
class MockServiceProvider extends \Elgg\Di\ServiceProvider {

	/**
	 * Constructor
	 *
	 * @param \Elgg\Config $config Config
	 */
	public function __construct(\Elgg\Config $config) {

		parent::__construct($config);

		$this->setFactory('session', function(MockServiceProvider $sp) {
			return \ElggSession::getMock();
		});

		$this->setFactory('db', function (MockServiceProvider $sp) {
			$config = $sp->dbConfig;
			$db = new \Elgg\Mocks\Database($config);
			$db->setLogger($sp->logger);

			return $db;
		});

		$this->setFactory('entityTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\EntityTable(
				$sp->config,
				$sp->db,
				$sp->entityCache,
				$sp->metadataCache,
				$sp->subtypeTable,
				$sp->hooks->getEvents(),
				$sp->session,
				$sp->translator,
				$sp->logger
			);
		});

		$this->setFactory('metadataTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\MetadataTable($sp->metadataCache, $sp->db, $sp->entityTable, $sp->hooks->getEvents(), $sp->session);
		});

		$this->setFactory('annotations', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\Annotations($sp->db, $sp->session, $sp->hooks->getEvents());
		});

		$this->setFactory('relationshipsTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\RelationshipsTable($sp->db, $sp->entityTable, $sp->metadataTable, $sp->hooks->getEvents());
		});

		$this->setFactory('subtypeTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\SubtypeTable($sp->db);
		});

		$this->setFactory('accessCollections', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\AccessCollections(
				$sp->config,
				$sp->db,
				$sp->entityTable,
				$sp->userCapabilities,
				$sp->accessCache,
				$sp->hooks,
				$sp->session,
				$sp->translator
			);
		});

		$this->setFactory('privateSettings', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\PrivateSettingsTable(
				$sp->db,
				$sp->entityTable,
				$sp->pluginSettingsCache
			);
		});

		$this->setFactory('mailer', function(MockServiceProvider $sp) {
			return new \Zend\Mail\Transport\InMemory();
		});

		$this->setFactory('plugins', function (MockServiceProvider $sp) {
			$pool = new \Elgg\Cache\Pool\InMemory();

			return new \Elgg\Database\TestingPlugins($pool, $sp->pluginSettingsCache);
		});

		$this->setFactory('siteSecret', function(MockServiceProvider $sp) {
			return new \Elgg\Database\SiteSecret('z1234567890123456789012345678901');
		});

	}
}
