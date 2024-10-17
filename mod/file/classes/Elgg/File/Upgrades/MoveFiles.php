<?php

namespace Elgg\File\Upgrades;

use Elgg\Database\QueryBuilder;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Move ElggFile files to entity location
 */
class MoveFiles extends AsynchronousUpgrade {
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2022092801;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped(): bool {
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		return elgg_count_entities($this->getOptions());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		
		$batch = elgg_get_entities($this->getOptions([
			'offset' => $offset,
		]));
		
		/* @var $entity \ElggFile */
		foreach ($batch as $entity) {
			
			$old_file = new \ElggFile();
			$old_file->owner_guid = $entity->owner_guid; // owned by owner
			$old_file->setFilename($entity->getFilename());
			
			if (!$old_file->exists()) {
				$result->addSuccesses();
				$entity->_elgg_file_migrated = time();
				continue;
			}

			$old_location = $old_file->getFilenameOnFilestore();
			$new_file = new \ElggFile();
			$new_file->owner_guid = $entity->guid; // owned by entity
			$new_file->setFilename($entity->getFilename());
			$new_file->open('write');
			$new_file->close();
			
			if (!rename($old_location, $new_file->getFilenameOnFilestore())) {
				$result->addFailures();
				continue;
			}
			
			// generate master icon for new location
			if ($entity->simpletype === 'image') {
				$entity->saveIconFromElggFile($new_file);
			}
			
			$entity->_elgg_file_migrated = time();

			// remove thumbnails
			if ($entity->simpletype === 'image') {
				foreach (['thumb', 'smallthumb', 'largethumb', 'masterthumb'] as $prefix) {
					$old_thumb = new \ElggFile();
					$old_thumb->owner_guid = $entity->owner_guid;
					
					$base_filename = "{$entity->filestore_prefix}/{$prefix}{$entity->upload_time}" . pathinfo(elgg_strtolower($entity->originalfilename), PATHINFO_FILENAME);
					
					foreach (['jpg', 'webp'] as $extension) {
						$old_thumb->setFilename("{$base_filename}.{$extension}");
						$old_thumb->delete();
					}
				}
			}
			
			$result->addSuccesses();
		}
		
		return $result;
	}
	
	/**
	 * Options for elgg_get_entities()
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 * @see elgg_get_entities()
	 */
	protected function getOptions(array $options = []): array {
		$defaults = [
			'type' => 'object',
			'subtype' => 'file',
			'limit' => 100,
			'batch' => true,
			'batch_inc_offset' => $this->needsIncrementOffset(),
			'created_before' => $this->getUpgrade()->time_created,
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					$sub = $qb->subquery('metadata');
					$sub->select('entity_guid')
						->where($qb->compare('name', '=', '_elgg_file_migrated', ELGG_VALUE_STRING));
					
					return $qb->compare("{$main_alias}.guid", 'not in', $sub->getSQL());
				}
			],
		];
		
		return array_merge($defaults, $options);
	}
}
