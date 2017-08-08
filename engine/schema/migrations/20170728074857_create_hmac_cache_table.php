<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateHmacCacheTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_hmac_cache` (
	 * `hmac` varchar(255) CHARACTER SET utf8 NOT NULL,
	 * `ts` int(11) NOT NULL,
	 * PRIMARY KEY (`hmac`),
	 * KEY `ts` (`ts`)
	 * ) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("hmac_cache")) {
			return;
		}

		$table = $this->table("hmac_cache", [
			'id' => false,
			'primary_key' => ["hmac"],
			'engine' => "MEMORY",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('hmac', 'string', [
			'null' => false,
			'limit' => MysqlAdapter::TEXT_SMALL,
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('ts', 'integer', [
			'null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 11,
		]);

		$table->addIndex(['ts'], [
			'name' => "ts",
			'unique' => false,
		]);

		$table->save();

	}
}
