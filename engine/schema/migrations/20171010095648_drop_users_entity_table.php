<?php

use Phinx\Migration\AbstractMigration;

class DropUsersEntityTable extends AbstractMigration {
	/**
	 * Move users_entity attributes to metadata
	 */
	public function up() {

		if (!$this->hasTable('users_entity') || !$this->hasTable('metadata')) {
			return;
		}

		$prefix = $this->getAdapter()->getOption('table_prefix');
		$cols = [
			'name' => 'text',
			'username' => 'text',
			'password_hash' => 'text',
			'email' => 'text',
			'language' => 'text',
			'banned' => 'text',
			'admin' => 'text',
			'prev_last_action' => 'integer',
			'last_login' => 'integer',
			'prev_last_login' => 'integer',
		];
		$col_names = "'" . implode("', '", array_keys($cols)) . "'";
				
		$users_query = "SELECT * FROM {$prefix}users_entity LIMIT 100";
		while ($rows = $this->fetchAll($users_query)) {
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
				
				// special column last_action goes to last_action in entities table
				$this->execute("
					UPDATE {$prefix}entities SET last_action = {$row['last_action']}
					WHERE guid = {$row['guid']}
				");
				
				foreach ($cols as $col => $type) {
					$value = $row[$col];
					if (is_null($value) || $value === '') {
						continue;
					}
					
					$new_metadata_rows[] = [
						'entity_guid' => $row['guid'],
						'name' => $col,
						'value' => $value,
						'value_type' => $type,
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
			
			// remove from users so it does not get processed again in the next while loop
			$this->execute("
				DELETE FROM {$prefix}users_entity
				WHERE guid IN ({$guids})
			");
		}
		
		// all data migrated, so drop the table
		$this->dropTable('users_entity');
	}
}
