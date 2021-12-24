<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SetCommentsLevelOne extends AbstractMigration {
    /**
     * Add level = 1 metadata to comments
     */
    public function up() {
		$prefix = $this->getAdapter()->getOption('table_prefix');
		
		$sub_query = "SELECT entity_guid FROM {$prefix}metadata WHERE name = 'level'";
		$time_created = time();
		
		$this->execute("
			INSERT INTO {$prefix}metadata (entity_guid, name, value, value_type, time_created)
			SELECT
				e.guid AS entity_guid,
				'level' AS name,
				1 AS value,
				'integer' AS value_type,
				{$time_created} AS time_created
			FROM {$prefix}entities e
			WHERE
				e.guid NOT IN ({$sub_query}) AND
				e.type = 'object' AND
				e.subtype = 'comment'
		");
    }
}
