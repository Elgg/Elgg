<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MergePrivateSettingsInMetadata extends AbstractMigration {
    /**
     * Moves private settings to metadata and drops the private settings table
     */
    public function change(): void {

        if (!$this->hasTable('private_settings')) {
            return;
        }

        $prefix = $this->getAdapter()->getOption('table_prefix');
        $time_created = time();

        $this->execute("
			INSERT INTO {$prefix}metadata (entity_guid, name, value, value_type, time_created)
			SELECT
				ps.entity_guid AS entity_guid,
				ps.name AS name,
				ps.value AS value,
				'text' AS value_type,
				{$time_created} AS time_created
			FROM {$prefix}private_settings ps
		");

        // all data migrated, so drop the table
        $this->table('private_settings')->drop()->save();
    }
}
