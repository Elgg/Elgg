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
			$row = $this->db->getDataRow("
				SELECT url FROM {$this->db->prefix}sites_entity
				WHERE guid = 1
			");

			if ($row) {
				$lines = [
					"",
					"/**",
					" * The installation root URL of the site. E.g. https://example.org/elgg/",
					" *",
					" * If not provided, this is sniffed from the Symfony Request object",
					" *",
					" * @global string \$CONFIG->wwwroot",
					" */",
					"\$CONFIG->wwwroot = '{$row->url}';",
					""
				];
				$bytes = implode(PHP_EOL, $lines);

				$this->append($bytes);

				return $row->url;
			} else {
				error_log("The DB table {$this->db->prefix}sites_entity did not have 'url'.");
			}
		} catch (\DatabaseException $ex) {
			error_log($ex->getMessage());
		}
	}
}