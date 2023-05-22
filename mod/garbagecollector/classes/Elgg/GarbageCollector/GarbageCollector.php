<?php

namespace Elgg\GarbageCollector;

use Elgg\Application\Database;
use Elgg\Database\Delete;
use Elgg\I18n\Translator;
use Elgg\Traits\Di\ServiceFacade;

/**
 * Garbage collecting service
 */
class GarbageCollector {

	use ServiceFacade;

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
	 * @return \stdClass[]
	 */
	public function optimize(): array {
		$dbprefix = $this->db->prefix;
		$output = [];

		$output[] = (object) [
			'operation' => $this->translator->translate('garbagecollector:start'),
			'result' => true,
			'completed' => new \DateTime(),
		];

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
		}

		$output[] = (object) [
			'operation' => $this->translator->translate('garbagecollector:done'),
			'result' => true,
			'completed' => new \DateTime(),
		];

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
		$results = self::instance()->cleanupOrphanedData();
		
		foreach ($results as $result) {
			echo $result->operation . ': ' . $result->num_rows . '. Completed: ' . $result->completed->format(DATE_ATOM) . PHP_EOL;
		}
		
		echo elgg_echo('garbagecollector:orphaned:done') . PHP_EOL . PHP_EOL;
	}
	
	/**
	 * Go through the database tables and remove orphaned data
	 *
	 * @return \stdClass[]
	 */
	public function cleanupOrphanedData(): array {
		$output = [];
		
		$output[] = $this->cleanupAccessCollections();
		$output[] = $this->cleanupAccessCollectionMembership();
		$output[] = $this->cleanupAnnotations();
		$output[] = $this->cleanupDelayedEmailQueue();
		$output[] = $this->cleanupEntityRelationships();
		$output[] = $this->cleanupMetadata();
		
		return $output;
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
		return $result->rowCount();
	}
	
	/**
	 * Remove access collections where (AND):
	 * - owner_guid no longer exist as guid in the entities table
	 * - id is not used in the entities table as access_id
	 * - id is not used in the annotations table as access_id
	 *
	 * @return \stdClass
	 */
	protected function cleanupAccessCollections(): \stdClass {
		$delete = Delete::fromTable('access_collections');
		
		$owner_sub = $delete->subquery('entities');
		$owner_sub->select('guid');
		
		$entities_access_id_sub = $delete->subquery('entities');
		$entities_access_id_sub->select('DISTINCT access_id');
		
		$annotations_access_id_sub = $delete->subquery('annotations');
		$annotations_access_id_sub->select('DISTINCT access_id');
		
		$delete->where($delete->merge([
			$delete->compare('owner_guid', 'not in', $owner_sub->getSQL()),
			$delete->compare('id', 'not in', $entities_access_id_sub->getSQL()),
			$delete->compare('id', 'not in', $annotations_access_id_sub->getSQL()),
		], 'AND'));
		
		return (object) [
			'operation' => $this->translator->translate('garbagecollector:orphaned', ['access_collections']),
			'num_rows' => $this->db->deleteData($delete),
			'completed' => new \DateTime(),
		];
	}
	
	/**
	 * Remove access collection memberships where (OR):
	 * - user_guid no longer exists in the entities table
	 * - access_collection_id no longer exists in the access_collections table
	 *
	 * @return \stdClass
	 */
	protected function cleanupAccessCollectionMembership(): \stdClass {
		$delete = Delete::fromTable('access_collection_membership');
		
		$user_sub = $delete->subquery('entities');
		$user_sub->select('guid');
		
		$access_collection_sub = $delete->subquery('access_collections');
		$access_collection_sub->select('id');
		
		$delete->where($delete->merge([
			$delete->compare('user_guid', 'not in', $user_sub->getSQL()),
			$delete->compare('access_collection_id', 'not in', $access_collection_sub->getSQL()),
		], 'OR'));
		
		return (object) [
			'operation' => $this->translator->translate('garbagecollector:orphaned', ['access_collection_membership']),
			'num_rows' => $this->db->deleteData($delete),
			'completed' => new \DateTime(),
		];
	}
	
	/**
	 * Remove annotations where:
	 * - entity_guid no longer exists in the entities table
	 *
	 * @return \stdClass
	 */
	protected function cleanupAnnotations(): \stdClass {
		$delete = Delete::fromTable('annotations');
		
		$entity_sub = $delete->subquery('entities');
		$entity_sub->select('guid');
		
		$delete->where($delete->compare('entity_guid', 'not in', $entity_sub->getSQL()));
		
		return (object) [
			'operation' => $this->translator->translate('garbagecollector:orphaned', ['annotations']),
			'num_rows' => $this->db->deleteData($delete),
			'completed' => new \DateTime(),
		];
	}
	
	/**
	 * Remove delayed emails where:
	 * - recipient_guid no longer exists in the entities table
	 *
	 * @return \stdClass
	 */
	protected function cleanupDelayedEmailQueue(): \stdClass {
		$delete = Delete::fromTable('delayed_email_queue');
		
		$entity_sub = $delete->subquery('entities');
		$entity_sub->select('guid');
		
		$delete->where($delete->compare('recipient_guid', 'not in', $entity_sub->getSQL()));
		
		return (object) [
			'operation' => $this->translator->translate('garbagecollector:orphaned', ['delayed_email_queue']),
			'num_rows' => $this->db->deleteData($delete),
			'completed' => new \DateTime(),
		];
	}
	
	/**
	 * Remove entity relationships where (OR):
	 * - guid_one no longer exists in the entities table
	 * - guid_two no longer exists in the entities table
	 *
	 * @return \stdClass
	 */
	protected function cleanupEntityRelationships(): \stdClass {
		$delete = Delete::fromTable('entity_relationships');
		
		$guid_sub = $delete->subquery('entities');
		$guid_sub->select('guid');
		
		$delete->where($delete->merge([
			$delete->compare('guid_one', 'not in', $guid_sub->getSQL()),
			$delete->compare('guid_two', 'not in', $guid_sub->getSQL()),
		], 'OR'));
		
		return (object) [
			'operation' => $this->translator->translate('garbagecollector:orphaned', ['entity_relationships']),
			'num_rows' => $this->db->deleteData($delete),
			'completed' => new \DateTime(),
		];
	}
	
	/**
	 * Remove metadata where:
	 * - entity_guid no longer exists in the entities table
	 *
	 * @return \stdClass
	 */
	protected function cleanupMetadata(): \stdClass {
		$delete = Delete::fromTable('metadata');
		
		$entity_guid_sub = $delete->subquery('entities');
		$entity_guid_sub->select('guid');
		
		$delete->where($delete->compare('entity_guid', 'not in', $entity_guid_sub->getSQL()));
		
		return (object) [
			'operation' => $this->translator->translate('garbagecollector:orphaned', ['metadata']),
			'num_rows' => $this->db->deleteData($delete),
			'completed' => new \DateTime(),
		];
	}
}
