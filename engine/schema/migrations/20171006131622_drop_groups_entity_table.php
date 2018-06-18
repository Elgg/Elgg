<?php

use Phinx\Migration\AbstractMigration;

class DropGroupsEntityTable extends AbstractMigration
{
	/**
	 * Move groups_entity attributes to metadata
	 */
	public function up() {

		if (!$this->hasTable('groups_entity') || !$this->hasTable('metadata')) {
			return;
		}

		$prefix = $this->getAdapter()->getOption('table_prefix');
		$cols = ['name', 'description'];
		$col_names = "'" . implode("', '", $cols) . "'";
		
		$groups_query = "SELECT * FROM {$prefix}groups_entity LIMIT 100";
		while ($rows = $this->fetchAll($groups_query)) {
			$guids = [];
			foreach ($rows as $row) {
				$guids[] = $row['guid'];
			}
			
			$guids = implode(',', $guids);
			
			// remove existing metadata... attributes are more important
			$this->execute("
				DELETE FROM {$prefix}metadata
				WHERE entity_guid IN ({$guids}) AND
				name IN ({$col_names})
			");
			
			$new_metadata_rows = [];
			
			foreach ($rows as $row) {
				foreach ($cols as $col) {
					$value = $row[$col];
					if (is_null($value) || $value === '') {
						continue;
					}
					
					$new_metadata_rows[] = [
						'entity_guid' => $row['guid'],
						'name' => $col,
						'value' => $value,
						'value_type' => 'text',
						'owner_guid' => 0,
						'access_id' => 2,
						'time_created' => time(),
						'enabled' => 'yes',
					];
				}
			}
			
			if (!empty($new_metadata_rows)) {
				$this->insert('metadata', $new_metadata_rows);
			}
			
			// remove from groups so it does not get processed again in the next while loop
			$this->execute("
				DELETE FROM {$prefix}groups_entity
				WHERE guid IN ({$guids})
			");
		}
		
		// all data migrated, so drop the table
		$this->dropTable('groups_entity');
	}
}
