<?php

use Phinx\Migration\AbstractMigration;

class CreateGeoCacheTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_geocode_cache` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `location` varchar(128) DEFAULT NULL,
	 * `lat` varchar(20) DEFAULT NULL,
	 * `long` varchar(20) DEFAULT NULL,
	 * PRIMARY KEY (`id`),
	 * UNIQUE KEY `location` (`location`)
	 * ) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4;
	 */
	public function change() {

		if ($this->hasTable("geocode_cache")) {
			return;
		}

		$table = $this->table("geocode_cache", [
			'engine' => "MEMORY",
			'encoding' => "utf8mb4",
			'collation' => "utf8mb4_general_ci",
		]);

		$table->addColumn('location', 'string', [
			'null' => true,
			'limit' => 128,
		]);

		$table->addColumn('lat', 'string', [
			'null' => true,
			'limit' => 20,
		]);

		$table->addColumn('long', 'string', [
			'null' => true,
			'limit' => 20,
		]);

		$table->addIndex(['location'], [
			'name' => "location",
			'unique' => true
		]);

		$table->save();

	}
}
