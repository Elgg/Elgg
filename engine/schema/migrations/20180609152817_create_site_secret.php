<?php

use Phinx\Migration\AbstractMigration;

/**
 * Create a site secret if one hasn't been created yet by an Installer
 */
class CreateSiteSecret extends AbstractMigration {

	/**
	 * {@inheritdoc}
	 */
	public function change() {

		$config_key = \Elgg\Database\SiteSecret::CONFIG_KEY;

		$prefix = $this->getAdapter()->getOption('table_prefix');

		$secret = $this->fetchRow("
			SELECT value
			FROM {$prefix}config
			WHERE name = '$config_key'
		");

		if (empty($secret) || empty($secret['value'])) {
			$crypto = new ElggCrypto();
			$hash = 'z' . $crypto->getRandomString(31);

			$this->insert('config', [
				'name' => $config_key,
				'value' => serialize($hash),
			]);
		}
	}
}
