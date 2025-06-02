<?php

namespace Elgg\Upgrades;

use Elgg\Database\AnnotationsTable;
use Elgg\Database\MetadataTable;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\Upgrade\Result;
use Elgg\Upgrade\SystemUpgrade;

class UpdateMetastringBoolDatabaseValue extends SystemUpgrade {
	
	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): int {
		return 2025060201;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped(): bool {
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function countItems(): int {
		$annotations = Select::fromTable(AnnotationsTable::TABLE_NAME);
		$annotations->select('count(*) as total')
			->where($annotations->compare('value', '=', '', ELGG_VALUE_STRING))
			->andWhere($annotations->compare('value_type', '=', 'bool', ELGG_VALUE_STRING));
		
		$metadata = Select::fromTable(MetadataTable::TABLE_NAME);
		$metadata->select('count(*) as total')
			->where($metadata->compare('value', '=', '', ELGG_VALUE_STRING))
			->andWhere($metadata->compare('value_type', '=', 'bool', ELGG_VALUE_STRING));
		
		$row = _elgg_services()->db->getDataRow($annotations);
		$count = (int) $row->total;
		
		$row = _elgg_services()->db->getDataRow($metadata);
		$count += (int) $row->total;
		
		return $count;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset): Result {
		$annotations = Update::table(AnnotationsTable::TABLE_NAME);
		$annotations->set('value', $annotations->param(0, ELGG_VALUE_INTEGER))
			->where($annotations->compare('value', '=', '', ELGG_VALUE_STRING))
			->andWhere($annotations->compare('value_type', '=', 'bool', ELGG_VALUE_STRING));
		
		$result->addSuccesses(_elgg_services()->db->updateData($annotations, true));
		
		$metadata = Update::table(MetadataTable::TABLE_NAME);
		$metadata->set('value', $metadata->param(0, ELGG_VALUE_INTEGER))
			->where($metadata->compare('value', '=', '', ELGG_VALUE_STRING))
			->andWhere($metadata->compare('value_type', '=', 'bool', ELGG_VALUE_STRING));
		
		$result->addSuccesses(_elgg_services()->db->updateData($metadata, true));
		
		return $result;
	}
}