<?php

use Phinx\Migration\AbstractMigration;

class CreateFriendsAccessCollections extends AbstractMigration {
    public function up() {
		if (!$this->hasTable('entities') || !$this->hasTable('access_collections') || !$this->hasTable('access_collection_membership')) {
			return;
		}

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$users_query = "
			SELECT e.* FROM {$prefix}entities e
			WHERE e.type = 'user'
			AND e.guid NOT IN (
				SELECT ac.owner_guid FROM {$prefix}access_collections ac
				WHERE ac.owner_guid = e.guid
				AND ac.subtype = 'friends'
			)
			LIMIT 25
		";
		
		while ($users = $this->fetchAll($users_query)) {
			foreach ($users as $user) {
				// create access collection for user friends
				$this->insert('access_collections', [
					'name' => 'friends',
					'owner_guid' => $user['guid'],
					'subtype' => 'friends',
				]);
				
				// retrieve just created acl
				$access_collection_query = "
					SELECT * FROM {$prefix}access_collections
					WHERE owner_guid = {$user['guid']}
					AND subtype = 'friends'
				";
				
				$acl = $this->fetchRow($access_collection_query);
				
				// get users friends
				$friends_query = "
					SELECT guid_two AS guid FROM {$prefix}entity_relationships
					WHERE guid_one = {$user['guid']}
					AND relationship = 'friend'
				";
				$friends = $this->fetchAll($friends_query);
				foreach ($friends as $friend) {
					// add friend to user access collection
					$this->insert('access_collection_membership', [
						'access_collection_id' => $acl['id'],
						'user_guid' => $friend['guid'],
					]);
				}
			}
		}
    }
}
