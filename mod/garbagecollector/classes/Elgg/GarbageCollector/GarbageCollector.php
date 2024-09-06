<?php

namespace Elgg\GarbageCollector;

use Elgg\Application\Database;
use Elgg\Database\AccessCollections;
use Elgg\Database\AnnotationsTable;
use Elgg\Database\DelayedEmailQueueTable;
use Elgg\Database\Delete;
use Elgg\Database\EntityTable;
use Elgg\Database\MetadataTable;
use Elgg\Database\RelationshipsTable;
use Elgg\I18n\Translator;
use Elgg\Queue\DatabaseQueue;
use Elgg\Traits\Di\ServiceFacade;
use Elgg\Traits\Loggable;

/**
 * Garbage collecting service
 */
class GarbageCollector {

	use ServiceFacade;
	use Loggable;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @var array
	 */
	protected $tables;

	/**
	 * Constructor
	 *
	 * @param Database   $db         Database
	 * @param Translator $translator Translator
	 */
	public function __construct(Database $db, Translator $translator) {
		$this->db = $db;
		$this->translator = $translator;
	}

	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	public static function name() {
		return 'garbagecollector';
	}

	/**
	 * Optimize the database
	 *
	 * @param bool $use_logger Add the results to the log (default: false)
	 *
	 * @return \stdClass[]
	 */
	public function optimize(bool $use_logger = false): array {
		$dbprefix = $this->db->prefix;
		$output = [];
		
		$output[] = (object) [
			'operation' => $this->translator->translate('garbagecollector:start'),
			'result' => true,
			'completed' => new \DateTime(),
		];
		
		if ($use_logger) {
			$this->getLogger()->notice($this->translator->translate('garbagecollector:start'));
		}
		
		foreach ($this->tables() as $table) {
			if (stripos($table, "{$dbprefix}system_log_") === 0) {
				// rotated system_log tables don't need to be optimized
				continue;
			}
			
			$result = $this->optimizeTable($table) !== 0;
			$output[] = (object) [
				'operation' => $this->translator->translate('garbagecollector:optimize', [$table]),
				'result' => $result,
				'completed' => new \DateTime(),
			];
			
			if ($use_logger) {
				$this->getLogger()->notice($this->translator->translate('garbagecollector:optimize', [$table]) . ': ' . ($result ? 'OK' : 'FAILED'));
			}
		}
		
		$output[] = (object) [
			'operation' => $this->translator->translate('garbagecollector:done'),
			'result' => true,
			'completed' => new \DateTime(),
		];
		
		if ($use_logger) {
			$this->getLogger()->notice($this->translator->translate('garbagecollector:done'));
		}
		
		return $output;
	}
	
	/**
	 * Listen to the garbage collection event
	 *
	 * @param \Elgg\Event $event 'gc', 'system'
	 *
	 * @return void
	 */
	public static function gcCallback(\Elgg\Event $event): void {
		$cron_logger = $event->getParam('logger');
		
		$instance = self::instance();
		$instance->setLogger($cron_logger);
		$instance->cleanupOrphanedData();
		
		$cron_logger->notice(elgg_echo('garbagecollector:orphaned:done'));
	}
	
	/**
	 * Go through the database tables and remove orphaned data
	 *
	 * @return void
	 */
	public function cleanupOrphanedData(): void {
		$this->cleanupAccessCollections();
		$this->cleanupAccessCollectionMembership();
		$this->cleanupAnnotations();
		$this->cleanupDelayedEmailQueue();
		$this->cleanupEntityRelationships();
		$this->cleanupMetadata();
	}

	/**
	 * Get a list of DB tables
	 *
	 * @return array
	 */
	protected function tables(): array {
		if (isset($this->tables)) {
			return $this->tables;
		}

		$table_prefix = $this->db->prefix;
		$result = $this->db->getConnection('read')->executeQuery("SHOW TABLES LIKE '{$table_prefix}%'");

		$this->tables = [];

		$rows = $result->fetchAllAssociative();
		foreach ($rows as $row) {
			if (empty($row)) {
				continue;
			}

			foreach ($row as $element) {
				$this->tables[] = $element;
			}
		}

		return $this->tables;
	}

	/**
	 * Optimize table
	 *
	 * @param string $table Table
	 *
	 * @return int
	 */
	protected function optimizeTable(string $table): int {
		$result = $this->db->getConnection('write')->executeQuery("OPTIMIZE TABLE {$table}");
		return (int) $result->rowCount();
	}
	
	/**
	 * Remove access collections where (AND):
	 * - owner_guid no longer exist as guid in the entities table
	 * - id is not used in the entities table as access_id
	 * - id is not used in the annotations table as access_id
	 *
	 * @return void
	 */
	protected function cleanupAccessCollections(): void {
		$delete = Delete::fromTable(AccessCollections::TABLE_NAME);
		
		$owner_sub = $delete->subquery(EntityTable::TABLE_NAME);
		$owner_sub->select('guid');
		
		$entities_access_id_sub = $delete->subquery(EntityTable::TABLE_NAME);
		$entities_access_id_sub->select('DISTINCT access_id');
		
		$annotations_access_id_sub = $delete->subquery(AnnotationsTable::TABLE_NAME);
		$annotations_access_id_sub->select('DISTINCT access_id');
		
		$delete->where($delete->merge([
			$delete->compare('owner_guid', 'not in', $owner_sub->getSQL()),
			$delete->compare('id', 'not in', $entities_access_id_sub->getSQL()),
			$delete->compare('id', 'not in', $annotations_access_id_sub->getSQL()),
		], 'AND'));
		
		$this->getLogger()->notice($this->translator->translate('garbagecollector:orphaned', ['access_collections']) . ': ' . $this->db->deleteData($delete));
	}
	
	/**
	 * Remove access collection memberships where (OR):
	 * - user_guid no longer exists in the entities table
	 * - access_collection_id no longer exists in the access_collections table
	 *
	 * @return void
	 */
	protected function cleanupAccessCollectionMembership(): void {
		$delete = Delete::fromTable(AccessCollections::MEMBERSHIP_TABLE_NAME);
		
		$user_sub = $delete->subquery(EntityTable::TABLE_NAME);
		$user_sub->select('guid');
		
		$access_collection_sub = $delete->subquery(AccessCollections::TABLE_NAME);
		$access_collection_sub->select('id');
		
		$delete->where($delete->merge([
			$delete->compare('user_guid', 'not in', $user_sub->getSQL()),
			$delete->compare('access_collection_id', 'not in', $access_collection_sub->getSQL()),
		], 'OR'));
		
		$this->getLogger()->notice($this->translator->translate('garbagecollector:orphaned', ['access_collection_membership']) . ': ' . $this->db->deleteData($delete));
	}
	
	/**
	 * Remove annotations where:
	 * - entity_guid no longer exists in the entities table
	 *
	 * @return void
	 */
	protected function cleanupAnnotations(): void {
		$delete = Delete::fromTable(AnnotationsTable::TABLE_NAME);
		
		$entity_sub = $delete->subquery(EntityTable::TABLE_NAME);
		$entity_sub->select('guid');
		
		$delete->where($delete->compare('entity_guid', 'not in', $entity_sub->getSQL()));
		
		$this->getLogger()->notice($this->translator->translate('garbagecollector:orphaned', ['annotations']) . ': ' . $this->db->deleteData($delete));
	}
	
	/**
	 * Remove delayed emails where:
	 * - recipient_guid no longer exists in the entities table
	 *
	 * @return void
	 */
	protected function cleanupDelayedEmailQueue(): void {
		$delete = Delete::fromTable(DelayedEmailQueueTable::TABLE_NAME);
		
		$entity_sub = $delete->subquery(EntityTable::TABLE_NAME);
		$entity_sub->select('guid');
		
		$delete->where($delete->compare('recipient_guid', 'not in', $entity_sub->getSQL()));
		
		$this->getLogger()->notice($this->translator->translate('garbagecollector:orphaned', ['delayed_email_queue']) . ': ' . $this->db->deleteData($delete));
	}
	
	/**
	 * Remove entity relationships where (OR):
	 * - guid_one no longer exists in the entities table
	 * - guid_two no longer exists in the entities table
	 *
	 * @return void
	 */
	protected function cleanupEntityRelationships(): void {
		$delete = Delete::fromTable(RelationshipsTable::TABLE_NAME);
		
		$guid_sub = $delete->subquery(EntityTable::TABLE_NAME);
		$guid_sub->select('guid');
		
		$delete->where($delete->merge([
			$delete->compare('guid_one', 'not in', $guid_sub->getSQL()),
			$delete->compare('guid_two', 'not in', $guid_sub->getSQL()),
		], 'OR'));
		
		$this->getLogger()->notice($this->translator->translate('garbagecollector:orphaned', ['entity_relationships']) . ': ' . $this->db->deleteData($delete));
	}
	
	/**
	 * Remove metadata where:
	 * - entity_guid no longer exists in the entities table
	 *
	 * @return void
	 */
	protected function cleanupMetadata(): void {
		$delete = Delete::fromTable(MetadataTable::TABLE_NAME);
		
		$entity_guid_sub = $delete->subquery(EntityTable::TABLE_NAME);
		$entity_guid_sub->select('guid');
		
		$delete->where($delete->compare('entity_guid', 'not in', $entity_guid_sub->getSQL()));
		
		$this->getLogger()->notice($this->translator->translate('garbagecollector:orphaned', ['metadata']) . ': ' . $this->db->deleteData($delete));
	}
}
