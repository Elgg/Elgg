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
		
		$groups_query = "SELECT * FROM {$prefix}groups_entity LIMIT 25";
		while ($rows = $this->fetchAll($groups_query)) {
			foreach ($rows as $row) {
				foreach ($cols as $col) {
					// remove existing metadata... attributes are more important
					$this->execute("
						DELETE FROM {$prefix}metadata
						WHERE entity_guid = {$row['guid']} AND
						name = '{$col}'
					");
					
					$this->insert('metadata', [
						'entity_guid' => $row['guid'],
						'name' => $col,
						'value' => $row[$col],
						'value_type' => 'text',
						'owner_guid' => 0,
						'access_id' => 2,
						'time_created' => time(),
						'enabled' => 'yes',
					]);
				}
				
				// remove from groups so it does not get processed again in the next while loop
				$this->execute("
					DELETE FROM {$prefix}groups_entity
					WHERE guid = {$row['guid']}
				");
			}
		}
		
		// all data migrated, so drop the table
		$this->dropTable('groups_entity');
	}
}
