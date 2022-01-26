<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddEntitiesTypeSubtypePairColumn extends AbstractMigration {
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function change(): void {
		
		$prefix = $this->getAdapter()->getOption('table_prefix');
		
		$entities = $this->table('entities');
		
		if ($entities->hasColumn('type_subtype_pair')) {
			return;
		}
		
		$entities->addColumn('type_subtype_pair', 'string', [
			'null' => false,
			'limit' => 252, // keeping this inline with subtype columns limit
			'after' => 'subtype',
		])->update();

		$max_guid = $this->fetchAll("
			SELECT max(guid) as max_guid
			FROM {$prefix}entities
		");
		if (!empty($max_guid)) {
			$max_guid = $max_guid[0]['max_guid'];
			$batch_size = 100000;
			
			$num_batches = ceil($max_guid / $batch_size);
			for ($i = 1; $i <= $num_batches; $i++) {
				$guid_low = $batch_size * ($i - 1);
				$guid_limit = $batch_size * $i;
				
				// need to lock table for a potential massive row based update
				$this->query("LOCK TABLES {$prefix}entities WRITE");
				$this->query("
					UPDATE {$prefix}entities
					SET type_subtype_pair = CONCAT(type, '.', subtype)
					WHERE type_subtype_pair = ''
					AND guid >= $guid_low
					AND guid < {$guid_limit}
				");
				$this->query("UNLOCK TABLES");
			}
		}
		
		$entities->addIndex(['type_subtype_pair'], [
			'name' => 'type_subtype_pair',
			'unique' => false,
		])->addIndex(['type_subtype_pair', 'time_created'], [
			'name' => 'recent_content',
			'unique' => false,
		])->update();
	}
}
