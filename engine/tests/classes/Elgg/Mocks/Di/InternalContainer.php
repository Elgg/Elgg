<?php

namespace Elgg\Mocks\Di;

use Psr\Container\ContainerInterface;

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
 * @property-read \Elgg\Mocks\I18n\Translator                 $translator              Translator
 * @property-read \Elgg\Mocks\Database\UsersTable             $usersTable              Users table
 */
class InternalContainer extends \Elgg\Di\InternalContainer{

	/**
	 * {@inheritDoc}
	 */
	public static function factory(array $options = []): self {
		$container = parent::factory($options);
		
		$container->set('session', function (ContainerInterface $c) {
			return \ElggSession::getMock();
		});

		$container->set('db', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database($c->dbConfig, $c->queryCache);
		});

		$container->set('entityTable', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\EntityTable(
				$c->config,
				$c->db,
				$c->entityCache,
				$c->metadataCache,
				$c->privateSettingsCache,
				$c->events,
				$c->session,
				$c->translator
			);
		});
		
		$container->set('delayedEmailQueueTable', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\DelayedEmailQueueTable($c->db);
		});

		$container->set('metadataTable', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\MetadataTable($c->metadataCache, $c->db, $c->events, $c->entityTable);
		});

		$container->set('annotationsTable', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\AnnotationsTable($c->db, $c->events);
		});

		$container->set('relationshipsTable', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\RelationshipsTable($c->db, $c->entityTable, $c->metadataTable, $c->events);
		});

		$container->set('accessCollections', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\AccessCollections(
				$c->config,
				$c->db,
				$c->entityTable,
				$c->userCapabilities,
				$c->accessCache,
				$c->hooks,
				$c->session,
				$c->translator
			);
		});

		$container->set('privateSettings', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\PrivateSettingsTable(
				$c->db,
				$c->entityTable,
				$c->privateSettingsCache
			);
		});

		$container->set('configTable', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\ConfigTable($c->db, $c->boot);
		});

		$container->set('mailer', function (ContainerInterface $c) {
			return new \Laminas\Mail\Transport\InMemory();
		});

		$container->set('plugins', function (ContainerInterface $c) {
			return new \Elgg\Mocks\Database\Plugins(
				$c->dataCache->plugins,
				$c->db,
				$c->session,
				$c->events,
				$c->translator,
				$c->views,
				$c->privateSettingsCache,
				$c->config,
				$c->system_messages,
				$c->request->getContextStack()
			);
		});

		$container->set('siteSecret', function (ContainerInterface $c) {
			return new \Elgg\Database\SiteSecret('z1234567890123456789012345678901');
		});
		
		$container->set('translator', function(ContainerInterface $c) {
			return new \Elgg\Mocks\I18n\Translator($c->config, $c->locale);
		});

		$container->set('usersTable', function(ContainerInterface $c) {
			return new \Elgg\Mocks\Database\UsersTable($c->config, $c->db, $c->metadataTable);
		});

		$container->set('notifications', function(ContainerInterface $c) {
			$queue = new \Elgg\Queue\MemoryQueue();
			
			return new \Elgg\Notifications\NotificationsService($queue, $c->hooks, $c->session);
		});

		$container->set('mutex', function(ContainerInterface $c) {
			return new \Elgg\Mocks\Database\Mutex($c->db);
		});
		
		$container->set('hmacCacheTable', function(ContainerInterface $c) {
			$hmac = new \Elgg\Mocks\Database\HMACCacheTable($c->db);
			// HMAC lifetime is 25 hours (this should be related to the time drift allowed in header validation)
			$hmac->setTTL(90000);
			
			return $hmac;
		});
		
		$container->set('apiUsersTable', function(ContainerInterface $c) {
			return new \Elgg\Mocks\Database\ApiUsersTable($c->db, $c->crypto);
		});
		
		$container->set('usersApiSessionsTable', function(ContainerInterface $c) {
			return new \Elgg\Mocks\Database\UsersApiSessionsTable($c->db, $c->crypto);
		});
		
		return $container;
	}
}
