<?php
/**
 *
 */

namespace Elgg\Config;

/**
 * Migrates site url database value to settings.php
 *
 * @access private
 */
class WwwrootSettingMigrator extends SettingsMigrator {

	/**
	 * {@inheritdoc}
	 */
	public function migrate() {
		try {
			$value = false;
			
			$sites_table_exists = !empty($this->db->getDataRow("SHOW TABLES LIKE '{$this->db->prefix}sites_entity'"));
			if ($sites_table_exists) {
				$row = $this->db->getDataRow("
					SELECT url FROM {$this->db->prefix}sites_entity
					WHERE guid = 1
				");
				$value = $row->url;
			} else {
				$row = $this->db->getDataRow("
					SELECT value FROM {$this->db->prefix}metadata
					WHERE name = 'url' AND
					entity_guid = 1
				");
				$value = $row->value;
			}
			
			if ($value) {
				$value = rtrim($value, '/') . '/';
				$lines = [
					"",
					"/**",
					" * The installation root URL of the site. E.g. https://example.org/elgg/",
					" *",
					" * If not provided, this is sniffed from the Symfony Request object",
					" *",
					" * @global string \$CONFIG->wwwroot",
					" */",
					"\$CONFIG->wwwroot = \"{$value}\";",
					""
				];
				$bytes = implode(PHP_EOL, $lines);

				$this->append($bytes);

				return $value;
			} else {
				error_log("The DB table {$this->db->prefix}metadata did not have an 'url' for the site.");
			}
		} catch (\DatabaseException $ex) {
			error_log($ex->getMessage());
		}
	}
}