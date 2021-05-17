<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\EventsService;
use Elgg\Traits\TimeUsing;
use Elgg\ViewsService;

/**
 * River table database action
 *
 * @since 4.0
 * @internal
 */
class RiverTable {
	
	use TimeUsing;
	
	/**
	 * @var string name of the river database table
	 */
	const TABLE_NAME = 'river';
	
	/**
	 * @var AnnotationsTable
	 */
	protected $annotationsTable;
	
	/**
	 * @var Database
	 */
	protected $db;
	
	/**
	 * @var EntityTable
	 */
	protected $entityTable;
	
	/**
	 * @var EventsService
	 */
	protected $events;
	
	/**
	 * @var ViewsService
	 */
	protected $views;
	
	/**
	 * Create the river table service
	 *
	 * @param Database         $db               database service
	 * @param AnnotationsTable $annotationsTable annotations service
	 * @param EntityTable      $entityTable      entity table service
	 * @param EventsService    $events           events service
	 * @param ViewsService     $views            views service
	 */
	public function __construct(Database $db, AnnotationsTable $annotationsTable, EntityTable $entityTable, EventsService $events, ViewsService $views) {
		$this->annotationsTable = $annotationsTable;
		$this->db = $db;
		$this->entityTable = $entityTable;
		$this->events = $events;
		$this->views = $views;
	}
	
	/**
	 * Get a river item based on its ID
	 *
	 * @param int $id the ID of the river item
	 *
	 * @return \ElggRiverItem|null
	 */
	public function get(int $id): ?\ElggRiverItem {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('id', '=', $id, ELGG_VALUE_ID));
		
		$row = $this->db->getDataRow($select);
		if (empty($row)) {
			return null;
		}
		
		return new \ElggRiverItem($row);
	}
	
	/**
	 * Save a river item to the database
	 *
	 * @param \ElggRiverItem $item item to save
	 *
	 * @return bool
	 */
	public function create(\ElggRiverItem $item): bool {
		if ($item->id) {
			// already created
			return false;
		}
		
		if (!empty($item->view) && !$this->views->viewExists($item->view)) {
			return false;
		}
		
		if (empty($item->action_type)) {
			return false;
		}
		
		if (empty($item->subject_guid) || !$this->entityTable->exists($item->subject_guid)) {
			return false;
		}
		
		if (empty($item->object_guid) || !$this->entityTable->exists($item->object_guid)) {
			return false;
		}
		
		if (!empty($item->target_guid) && !$this->entityTable->exists($item->target_guid)) {
			return false;
		}
		
		if (!empty($item->annotation_id) && !$this->annotationsTable->get($item->annotation_id)) {
			return false;
		}
		
		$created = $item->posted ?? $this->getCurrentTime()->getTimestamp();
		
		if (!$this->events->triggerBefore('create', 'river', $item)) {
			return false;
		}
		
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'action_type' => $insert->param($item->action_type, ELGG_VALUE_STRING),
			'view' => $insert->param($item->view ?? '', ELGG_VALUE_STRING),
			'subject_guid' => $insert->param($item->subject_guid, ELGG_VALUE_GUID),
			'object_guid' => $insert->param($item->object_guid, ELGG_VALUE_GUID),
			'target_guid' => $insert->param($item->target_guid ?? 0, ELGG_VALUE_GUID),
			'annotation_id' => $insert->param($item->annotation_id ?? 0, ELGG_VALUE_ID),
			'posted' => $insert->param($created, ELGG_VALUE_TIMESTAMP),
		]);
		
		$id = $this->db->insertData($insert);
		if (empty($id)) {
			return false;
		}
		
		$item->id = $id;
		$item->posted = $created;
		
		$this->events->triggerAfter('create', 'river', $item);
		
		return true;
	}
	
	/**
	 * Delete a river item
	 *
	 * @param \ElggRiverItem $item the item to delete
	 *
	 * @return bool
	 */
	public function delete(\ElggRiverItem $item): bool {
		if (!$item->id) {
			return false;
		}
		
		if (!$this->events->triggerBefore('delete', 'river', $item)) {
			return false;
		}
		
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('id', '=', $item->id, ELGG_VALUE_ID));
		
		$result = (bool) $this->db->deleteData($delete);
		
		$this->events->triggerAfter('delete', 'river', $item);
		
		return $result;
	}
}
