<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateQueueTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_queue` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `name` varchar(255) CHARACTER SET utf8 NOT NULL,
	 * `data` mediumblob NOT NULL,
	 * `timestamp` int(11) NOT NULL,
	 * `worker` varchar(32) NULL,
	 * PRIMARY KEY (`id`),
	 * KEY `name` (`name`),
	 * KEY `retrieve` (`timestamp`,`worker`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("queue")) {
			return;
		}

		$table = $this->table("queue", [
			'engine' => "InnoDB",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('name', 'string', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_SMALL,
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('data', 'blob', [
			'null' => false,
			'limit' => MysqlAdapter::BLOB_MEDIUM,
		]);

		$table->addColumn('timestamp', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addColumn('worker', 'string', [
			'null' => true,
			'limit' => 32,
		]);

		$table->addIndex(['name'], [
			'name' => "name",
			'unique' => false,
		]);

		$table->addIndex([
			'timestamp',
			'worker'
		], [
			'name' => "retrieve",
			'unique' => false
		]);

		$table->save();
	}
}
