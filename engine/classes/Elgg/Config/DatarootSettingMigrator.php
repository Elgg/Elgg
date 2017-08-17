<?php
/**
 *
 */

namespace Elgg\Config;

/**
 * Migrates dataroot database value to settings.php
 *
 * @access private
 */
class DatarootSettingMigrator extends SettingsMigrator {

	/**
	 * {@inheritdoc}
	 */
	public function migrate() {

		try {
			$row = $this->db->getDataRow("
				SELECT value FROM {$this->db->prefix}datalists
				WHERE name = 'dataroot'
			");

			if ($row) {
				$value = $row->value;
				$lines = [
					"",
					"/**",
					" * The full file path for Elgg data storage. E.g. /path/to/elgg-data/",
					" *",
					" * @global string \$CONFIG->dataroot",
					" */",
					"\$CONFIG->dataroot = \"{$value}\";",
					""
				];
				$bytes = implode(PHP_EOL, $lines);

				$this->append($bytes);

				return $value;
			} else {
				error_log("The DB table {$this->db->prefix}datalists did not have 'dataroot'.");
			}
		} catch (\DatabaseException $ex) {
			error_log($ex->getMessage());
		}
	}
}