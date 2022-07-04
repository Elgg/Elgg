<?php

namespace Elgg\Mocks\Di;

use Psr\Container\ContainerInterface;

/**
 * Mocking service
 *
 * @property-read \Elgg\Mocks\Database\AccessCollections           $accessCollections               ACL table mock
 * @property-read \Elgg\Mocks\Database\AnnotationsTable            $annotationsTable                Annotation mocks
 * @property-read \Elgg\Mocks\Database                             $db                              Database
 * @property-read \Elgg\Mocks\Database\DelayedEmailQueueTable      $delayedEmailQueueTable          Delayed Email Queue Table mock
 * @property-read \Elgg\Mocks\Database\EntityTable                 $entityTable                     Entity mocks
 * @property-read \Elgg\Mocks\Database\HMACCacheTable              $hmacCacheTable                  HMAC Cache table
 * @property-read \Elgg\Mocks\Database\MetadataTable               $metadataTable                   Metadata mocks
 * @property-read \Elgg\Mocks\Database\Mutex                       $mutex                           Mutex
 * @property-read \Elgg\Notifications\NotificationsService         $notifications                   Notification service (with memory queue)
 * @property-read \Elgg\Mocks\Database\PrivateSettingsTable        $privateSettings                 Private settings table mock
 * @property-read \Elgg\Mocks\Database\RelationshipsTable          $relationshipsTable              Annotation mocks
 * @property-read \Elgg\Mocks\I18n\Translator                      $translator                      Translator
 * @property-read \Elgg\Mocks\Database\UsersTable                  $usersTable                      Users table
 * @property-read \Elgg\Mocks\Database\UsersRememberMeCookiesTable $users_remember_me_cookies_table Users remember me cookies table
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

		$container->set('db', \DI\autowire(\Elgg\Mocks\Database::class));
		$container->set('entityTable', \DI\autowire(\Elgg\Mocks\Database\EntityTable::class));
		$container->set('delayedEmailQueueTable', \DI\autowire(\Elgg\Mocks\Database\DelayedEmailQueueTable::class));
		$container->set('metadataTable', \DI\autowire(\Elgg\Mocks\Database\MetadataTable::class));
		$container->set('annotationsTable', \DI\autowire(\Elgg\Mocks\Database\AnnotationsTable::class));
		$container->set('relationshipsTable', \DI\autowire(\Elgg\Mocks\Database\RelationshipsTable::class));
		$container->set('accessCollections', \DI\autowire(\Elgg\Mocks\Database\AccessCollections::class)->constructorParameter('cache', \DI\get('accessCache')));
		$container->set('privateSettings', \DI\autowire(\Elgg\Mocks\Database\PrivateSettingsTable::class));
		$container->set('configTable', \DI\autowire(\Elgg\Mocks\Database\ConfigTable::class));
		$container->set('users_remember_me_cookies_table', \DI\autowire(\Elgg\Mocks\Database\UsersRememberMeCookiesTable::class));

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
				$c->request
			);
		});

		$container->set('siteSecret', function (ContainerInterface $c) {
			return new \Elgg\Database\SiteSecret('z1234567890123456789012345678901');
		});
		
		$container->set('translator', \DI\autowire(\Elgg\Mocks\I18n\Translator::class));
		$container->set('usersTable', \DI\autowire(\Elgg\Mocks\Database\UsersTable::class));

		$container->set('notifications', function(ContainerInterface $c) {
			$queue = new \Elgg\Queue\MemoryQueue();
			
			return new \Elgg\Notifications\NotificationsService($queue, $c->hooks, $c->session, $c->events);
		});

		$container->set('mutex', \DI\autowire(\Elgg\Mocks\Database\Mutex::class));
		$container->set('hmacCacheTable', \DI\autowire(\Elgg\Mocks\Database\HMACCacheTable::class));
		$container->set('apiUsersTable', \DI\autowire(\Elgg\Mocks\Database\ApiUsersTable::class));
		$container->set('usersApiSessionsTable', \DI\autowire(\Elgg\Mocks\Database\UsersApiSessionsTable::class));
				
		return $container;
	}
}
