<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddAclSubtype extends AbstractMigration {
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
	 *
	 * The following commands can be used in this method and Phinx will
	 * automatically reverse them when rolling back:
	 *
	 *    createTable
	 *    renameTable
	 *    addColumn
	 *    renameColumn
	 *    addIndex
	 *    addForeignKey
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function change() {
		$table = $this->table('access_collections');
		$prefix = $this->getAdapter()->getOption('table_prefix');
		
		if (!$table->hasColumn('subtype')) {
			$table->addColumn('subtype', 'string', [
				'null' => true,
				'limit' => MysqlAdapter::TEXT_SMALL,
			]);
		
			$table->save();
		}
		
		// add friends collection subtype to user owned acls
		// this was the assumed usage of user owned acls
		$this->query("
			UPDATE {$prefix}{$table->getName()} acl
			INNER JOIN {$prefix}entities e ON acl.owner_guid = e.guid
			SET acl.subtype = 'friends_collection'
			WHERE e.type = 'user'
		");
		
		// add group_acl subtype to group owned acls as tracked in group_acl metadata
		$this->query("
			UPDATE {$prefix}{$table->getName()} acl
			INNER JOIN {$prefix}metadata md ON acl.owner_guid = md.entity_guid
			INNER JOIN {$prefix}entities e ON md.entity_guid = e.guid
			SET acl.subtype = 'group_acl'
			WHERE md.name = 'group_acl'
			AND md.value = acl.id
			AND e.type = 'group'
		");
		
		// remove the migrated group_acl metadata
		$this->query("
			DELETE md FROM {$prefix}metadata md
			INNER JOIN {$prefix}entities e ON md.entity_guid = e.guid
			INNER JOIN {$prefix}{$table->getName()} acl ON md.value = acl.id
			WHERE md.name = 'group_acl'
			AND e.type = 'group'
		");
	}
}
