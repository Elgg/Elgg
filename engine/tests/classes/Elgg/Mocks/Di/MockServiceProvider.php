<?php

namespace Elgg\Mocks\Di;

/**
 * Mocking service
 *
 * @property-read \Elgg\Mocks\Database\AccessCollections      $accessCollections       ACL table mock
 * @property-read \Elgg\Mocks\Database\AnnotationsTable       $annotationsTable        Annotation mocks
 * @property-read \Elgg\Mocks\Database                        $db                      Database
 * @property-read \Elgg\Mocks\Database\DelayedEmailQueueTable $delayedEmailQueueTable  Delayed Email Queue Table mock
 * @property-read \Elgg\Mocks\Database\EntityTable            $entityTable             Entity mocks
 * @property-read \Elgg\Mocks\Database\HMACCacheTable         $hmacCacheTable          HMAC Cache table
 * @property-read \Elgg\Mocks\Database\MetadataTable          $metadataTable           Metadata mocks
 * @property-read \Elgg\Mocks\Database\Mutex                  $mutex                   Mutex
 * @property-read \Elgg\Notifications\NotificationsService    $notifications           Notification service (with memory queue)
 * @property-read \Elgg\Mocks\Database\PrivateSettingsTable   $privateSettings         Private settings table mock
 * @property-read \Elgg\Mocks\Database\RelationshipsTable     $relationshipsTable      Annotation mocks
 * @property-read \Elgg\Mocks\I18n\Translator				  $translator              Translator
 * @property-read \Elgg\Mocks\Database\UsersTable             $usersTable              Users table
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

		$this->setFactory('session', function (MockServiceProvider $sp) {
			return \ElggSession::getMock();
		});

		$this->setFactory('db', function (MockServiceProvider $sp) {
			$config = $sp->dbConfig;
			$db = new \Elgg\Mocks\Database($config, $sp->queryCache);

			return $db;
		});

		$this->setFactory('entityTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\EntityTable(
				$sp->config,
				$sp->db,
				$sp->entityCache,
				$sp->metadataCache,
				$sp->privateSettingsCache,
				$sp->events,
				$sp->session,
				$sp->translator
			);
		});
		
		$this->setFactory('delayedEmailQueueTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\DelayedEmailQueueTable($sp->db);
		});

		$this->setFactory('metadataTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\MetadataTable($sp->metadataCache, $sp->db, $sp->events, $sp->entityTable);
		});

		$this->setFactory('annotationsTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\AnnotationsTable($sp->db, $sp->events);
		});

		$this->setFactory('relationshipsTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\RelationshipsTable($sp->db, $sp->entityTable, $sp->metadataTable, $sp->events);
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
				$sp->privateSettingsCache
			);
		});

		$this->setFactory('configTable', function (MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\ConfigTable($sp->db, $sp->boot);
		});

		$this->setFactory('mailer', function (MockServiceProvider $sp) {
			return new \Laminas\Mail\Transport\InMemory();
		});

		$this->setFactory('plugins', function (MockServiceProvider $sp) {
			$cache = $sp->dataCache->plugins;

			return new \Elgg\Mocks\Database\Plugins(
				$cache,
				$sp->db,
				$sp->session,
				$sp->events,
				$sp->translator,
				$sp->views,
				$sp->privateSettingsCache,
				$sp->config,
				$sp->systemMessages,
				$sp->request->getContextStack()
			);
		});

		$this->setFactory('siteSecret', function (MockServiceProvider $sp) {
			return new \Elgg\Database\SiteSecret('z1234567890123456789012345678901');
		});
		
		$this->setFactory('translator', function(MockServiceProvider $sp) {
			return new \Elgg\Mocks\I18n\Translator($sp->config, $sp->localeService);
		});

		$this->setFactory('usersTable', function(MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\UsersTable($sp->config, $sp->db, $sp->metadataTable);
		});

		$this->setFactory('notifications', function(MockServiceProvider $c) {
			$queue = new \Elgg\Queue\MemoryQueue();
			
			return new \Elgg\Notifications\NotificationsService($queue, $c->hooks, $c->session);
		});

		$this->setFactory('mutex', function(MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\Mutex($sp->db);
		});
		
		$this->setFactory('hmacCacheTable', function(MockServiceProvider $sp) {
			$hmac = new \Elgg\Mocks\Database\HMACCacheTable($sp->db);
			// HMAC lifetime is 25 hours (this should be related to the time drift allowed in header validation)
			$hmac->setTTL(90000);
			
			return $hmac;
		});
		
		$this->setFactory('apiUsersTable', function(MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\ApiUsersTable($sp->db, $sp->crypto);
		});
		
		$this->setFactory('usersApiSessionsTable', function(MockServiceProvider $sp) {
			return new \Elgg\Mocks\Database\UsersApiSessionsTable($sp->db, $sp->crypto);
		});
	}
}
