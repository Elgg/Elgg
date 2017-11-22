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
			'last_action' => 'integer',
			'prev_last_action' => 'integer',
			'last_login' => 'integer',
			'prev_last_login' => 'integer',
		];
				
		$users_query = "SELECT * FROM {$prefix}users_entity LIMIT 25";
		while ($rows = $this->fetchAll($users_query)) {
			foreach ($rows as $row) {
				foreach ($cols as $col => $type) {
					if ($col == 'last_action') {
						// special column last_action goes to last_action in entities table
						$this->execute("
							UPDATE {$prefix}entities SET last_action = {$row['last_action']}
							WHERE guid = {$row['guid']}
						");
						continue;
					}
					
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
						'value_type' => $type,
						'owner_guid' => 0,
						'access_id' => 2,
						'time_created' => time(),
						'enabled' => 'yes',
					]);
				}
				
				// remove from users so it does not get processed again in the next while loop
				$this->execute("
					DELETE FROM {$prefix}users_entity
					WHERE guid = {$row['guid']}
				");
			}
		}
		
		// all data migrated, so drop the table
		$this->dropTable('users_entity');
	}
}
