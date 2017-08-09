<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateEntityRelationshipsTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_entity_relationships` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `guid_one` bigint(20) unsigned NOT NULL,
	 * `relationship` varchar(50) NOT NULL,
	 * `guid_two` bigint(20) unsigned NOT NULL,
	 * `time_created` int(11) NOT NULL,
	 * PRIMARY KEY (`id`),
	 * UNIQUE KEY `guid_one` (`guid_one`,`relationship`,`guid_two`),
	 * KEY `relationship` (`relationship`),
	 * KEY `guid_two` (`guid_two`)
	 * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("entity_relationships")) {
			return;
		}

		$table = $this->table("entity_relationships", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('guid_one', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('relationship', 'string', [
			'null' => false,
			'limit' => 50,
		]);

		$table->addColumn('guid_two', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_BIG,
			'precision' => 20,
			'signed' => false,
		]);

		$table->addColumn('time_created', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addIndex([
			'guid_one',
			'relationship',
			'guid_two'
		], [
			'name' => "guid_one",
			'unique' => true,
		]);

		$table->addIndex(['relationship'], [
			'name' => "relationship",
			'unique' => false,
		]);

		$table->addIndex(['guid_two'], [
			'name' => "guid_two",
			'unique' => false
		]);

		$table->save();

	}
}
