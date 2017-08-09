<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateEntitySubtypesTable extends AbstractMigration {
	/**
	 * CREATE TABLE `prefix_entity_subtypes` (
	 * `id` int(11) NOT NULL AUTO_INCREMENT,
	 * `type` enum('object','user','group','site') NOT NULL,
	 * `subtype` varchar(50) NOT NULL,
	 * `class` varchar(255) NOT NULL DEFAULT '',
	 * PRIMARY KEY (`id`),
	 * UNIQUE KEY `type` (`type`,`subtype`)
	 * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
	 *
	 * INSERT INTO `prefix_entity_subtypes`
	 * (type, subtype, class) VALUES
	 * ('object', 'plugin', 'ElggPlugin'),
	 * ('object', 'file', 'ElggFile'),
	 * ('object', 'widget', 'ElggWidget'),
	 * ('object', 'comment', 'ElggComment'),
	 * ('object', 'elgg_upgrade', 'ElggUpgrade'),
	 * ('object', 'admin_notice', '');
	 */
	public function change() {

		if ($this->hasTable("entity_subtypes")) {
			return;
		}

		$table = $this->table("entity_subtypes", [
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
		]);

		$table->addColumn('type', 'enum', [
			'null' => false,
			'limit' => 6,
			'values' => [
				'object',
				'user',
				'group',
				'site'
			],
		]);

		$table->addColumn('subtype', 'string', [
			'null' => false,
			'limit' => 50,
		]);

		$table->addColumn('class', 'string', [
			'null' => false,
			'default' => '',
			'limit' => 255,
		]);

		$table->addIndex([
			'type',
			'subtype'
		], [
			'name' => "type",
			'unique' => true
		]);

		$table->insert([
			[
				'type' => 'object',
				'subtype' => 'plugin',
				'class' => 'ElggPlugin',
			],
			[
				'type' => 'object',
				'subtype' => 'file',
				'class' => 'ElggFile',
			],
			[
				'type' => 'object',
				'subtype' => 'widget',
				'class' => 'ElggWidget',
			],
			[
				'type' => 'object',
				'subtype' => 'comment',
				'class' => 'ElggComment',
			],
			[
				'type' => 'object',
				'subtype' => 'elgg_upgrade',
				'class' => 'ElggUpgrade',
			],
			[
				'type' => 'object',
				'subtype' => 'admin_notice',
				'class' => '',
			],
		]);

		$table->save();
	}
}
