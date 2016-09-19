<?php
namespace Elgg\Database;

use Elgg\Database\Config as DbConfig;

/**
 * Builds a PDO object from Elgg's settings
 */
class PdoFactory {

	/**
	 * Build a PDO by loading the settings.php file.
	 *
	 * <code>
	 * list ($pdo, $prefix) = $factory->fromRootPath('/var/www/elgg');
	 * </code>
	 *
	 * @param string $root_path Root path of Elgg installation
	 * @param string $type      "readwrite", "read", or "write"
	 *
	 * @return [\PDO $pdo, string $dbprefix]
	 */
	public function fromRootPath($root_path, $type = DbConfig::READ_WRITE) {
		global $CONFIG;

		// may already be loaded
		if (!isset($CONFIG->dbprefix)) {
			if (file_exists("$root_path/elgg-config/settings.php")) {
				require "$root_path/elgg-config/settings.php";
			} elseif (file_exists("$root_path/engine/settings.php")) {
				require "$root_path/engine/settings.php";
			} else {
				throw new \RuntimeException('Cannot locate settings.php');
			}
		}

		$db_config = new DbConfig($CONFIG);
		if ($db_config->isDatabaseSplit()) {
			$arr = $db_config->getConnectionConfig(DbConfig::READ);
		} else {
			$arr = $db_config->getConnectionConfig(DbConfig::READ_WRITE);
		}

		$pdo = new \PDO(
			"mysql:host={$arr['host']};dbname={$arr['database']};charset=utf8",
			$arr['user'],
			$arr['password']
		);

		return [$pdo, $CONFIG->dbprefix];
	}
}
