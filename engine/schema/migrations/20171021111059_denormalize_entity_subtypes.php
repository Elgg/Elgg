<?php

use Elgg\Exceptions\Configuration\InstallationException;
use Phinx\Migration\AbstractMigration;

class DenormalizeEntitySubtypes extends AbstractMigration {

	/**
	 * Validate that migration is possible
	 */
	public function validate() {

		if (!$this->hasTable('entity_subtypes')) {
			return;
		}

		$reserved_subtypes = [
			'user' => [
				'user',
			],
			'group' => [
				'group',
			],
			'site' => [
				'site',
			],
		];

		$prefix = $this->getAdapter()->getOption('table_prefix');

		foreach ($reserved_subtypes as $type => $subtypes) {
			$subtypes_in = array_map(function ($e) {
				return "'$e'";
			}, $subtypes);

			$subtypes_in = implode(', ', $subtypes_in);

			$row = $this->fetchRow("
				SELECT count(*) as count
				FROM {$prefix}entity_subtypes
				WHERE type='$type'
				AND subtype IN ($subtypes_in)
			");

			if (!empty($row['count'])) {
				$class = __CLASS__;
				throw new InstallationException("
					Unable to perform migration {$class}, because the database contains entities with a reserved subtype name.
					Please ensure that you are not using one of the reserved subtypes [{$subtypes_in}]
					for entities of '{$type}' type before running the migration,
					otherwise you may loose important entity subtype bindings.
				");
			}
		}

		$row = $this->fetchRow("
				SELECT count(*) as count
				FROM {$prefix}entities
				WHERE type='object'
				AND subtype=0 OR subtype IS NULL
			");

		if (!empty($row['count'])) {
			$class = __CLASS__;
			throw new InstallationException("
					Unable to perform migration {$class}, because the database contains objects without a subtype.
					Please ensure that all object entities have a valid subtype associated with them.
					There are {$row->count} object without a subtype in your entities table.
				");
		}
	}

	/**
	 * Denormalize
	 */
	public function change() {

		$this->validate();

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$table = $this->table('entities');

		$table->renameColumn('subtype', 'subtype_id');
		$table->save();

		$table->addColumn('subtype', 'string', [
			'null' => false,
			'limit' => 50,
			'after' => 'type',
		]);
				
		$table->save();

		$this->query("
			UPDATE {$prefix}entities e
			JOIN {$prefix}entity_subtypes es ON e.subtype_id = es.id
			SET e.subtype = es.subtype
		");

		foreach (['user', 'group', 'site'] as $type) {
			$this->query("
				UPDATE {$prefix}entities e
				SET e.subtype = '{$type}'
				WHERE e.type = '{$type}' AND e.subtype_id = 0
			");
		}

		$table->removeColumn('subtype_id');
		
		$table->save();

		$this->table('entity_subtypes')->drop()->save();
	}
}
