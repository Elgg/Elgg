<?php

use Phinx\Migration\AbstractMigration;

class DropGeocodeCache extends AbstractMigration {

	/**
	 * Migrate Up.
	 */
	public function up() {
		$this->dropTable('geocode_cache');
	}

	/**
	 * Migrate Down.
	 */
	public function down() {
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
