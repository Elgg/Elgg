<?php

use Phinx\Migration\AbstractMigration;

class DropSitesEntityTable extends AbstractMigration
{
	/**
	 * Move sites_entity attributes to metadata
	 */
	public function up() {

		if (!$this->hasTable('sites_entity') || !$this->hasTable('metadata')) {
			return;
		}

		$prefix = $this->getAdapter()->getOption('table_prefix');
		$rows = $this->fetchAll("
			SELECT * FROM {$prefix}sites_entity
		");
		
		$cols = ['name', 'description', 'url'];

		foreach ($rows as $row) {
			foreach ($cols as $col) {
				// remove existing metadata... attributes are more important
				$this->execute("
					DELETE FROM {$prefix}metadata
					WHERE entity_guid = {$row['guid']} AND
					name = '{$col}'
				");
				
				$this->table('metadata')->insert([[
					'entity_guid' => $row['guid'],
					'name' => $col,
					'value' => $row[$col],
					'value_type' => 'text',
					'owner_guid' => 0,
					'access_id' => 2,
					'time_created' => time(),
					'enabled' => 'yes',
				]])->saveData();
			}
		}

		// all data migrated, so drop the table
		$this->table('sites_entity')->drop()->save();
	}
}
