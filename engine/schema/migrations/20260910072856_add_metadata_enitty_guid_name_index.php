<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddMetadataEnittyGuidNameIndex extends AbstractMigration {
 
	/**
	 * Add an index to the metadata table for the columns ('entity_guid', 'name')
	 *
	 * @return void
	 */
	public function change(): void {
		if (!$this->hasTable('metadata')) {
			return;
		}
		
		$table = $this->table('metadata');
		if ($table->hasIndexByName('entity_guid_name')) {
			return;
		}
		
		$table->addIndex(['entity_guid', 'name'], [
			'name' => 'entity_guid_name',
			'unique' => false,
			'limit' => [
				'name' => MysqlAdapter::INT_TINY,
			],
		]);
		
		$table->save();
	}
}
