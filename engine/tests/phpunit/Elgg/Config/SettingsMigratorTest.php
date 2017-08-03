<?php

namespace Elgg\Config;

use Elgg\Mocks\Database;
use Elgg\TestCase;

/**
 * @group  Config
 *
 * @covers DatarootSettingMigrator
 * @covers WwwrootSettingMigrator
 */
class SettingsMigratorTest extends TestCase {

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $settings_path;

	public function setUp() {
		parent::setUp();

		$config = $this->getTestingConfigArray();

		$this->db = new Database($this->getTestingDatabaseConfig());

		$settings_path = $config['dataroot'] . 'test.settings.php';

		$lines = [
			"<?php",
			"",
			"global \$CONFIG;",
			"if (!isset(\$CONFIG)) {",
			"	\$CONFIG = new \stdClass;",
			"}"
		];

		file_put_contents($settings_path, implode(PHP_EOL, $lines), FILE_APPEND | LOCK_EX);

		$this->settings_path = $settings_path;
	}

	public function tearDown() {
		unlink($this->settings_path);
	}

	public function testDatarootSettingMigration() {
		if (defined('HHVM_VERSION')) {
			$this->markTestSkipped();
		}

		$config = $this->getTestingConfigArray();

		$this->db->addQuerySpec([
			'sql' => "
				SELECT value FROM {$this->db->prefix}datalists
			  	WHERE name = 'dataroot'
			",
			'results' => [
				(object) [
					'value' => $config['dataroot'],
				],
			],
		]);

		$migrator = new DatarootSettingMigrator($this->db, $this->settings_path);
		$this->assertEquals($config['dataroot'], $migrator->migrate());

		// This is set by getTestingConfigArray()
		// We want it clear
		global $CONFIG;
		$CONFIG = new \stdClass();

		include $this->settings_path;

		$this->assertEquals($config['dataroot'], $CONFIG->dataroot);
	}

	public function testWwwrootSettingMigration() {
		if (defined('HHVM_VERSION')) {
			$this->markTestSkipped();
		}

		$config = $this->getTestingConfigArray();

		$this->db->addQuerySpec([
			'sql' => "
				SELECT url FROM {$this->db->prefix}sites_entity
				WHERE guid = 1
			",
			'results' => [
				(object) [
					'url' => $config['wwwroot'],
				],
			],
		]);

		$migrator = new WwwrootSettingMigrator($this->db, $this->settings_path);
		$this->assertEquals($config['wwwroot'], $migrator->migrate());

		// This is set by getTestingConfigArray()
		// We want it clear
		global $CONFIG;
		$CONFIG = new \stdClass();

		include $this->settings_path;

		$this->assertEquals($config['wwwroot'], $CONFIG->wwwroot);
	}

	/**
	 * Setup testing database config
	 *
	 * @return \Elgg\Database\DbConfig
	 */
	public function getTestingDatabaseConfig() {
		$conf = new \stdClass();
		$conf->db['read'][0]['dbhost'] = 0;
		$conf->db['read'][0]['dbuser'] = 'user0';
		$conf->db['read'][0]['dbpass'] = 'xxxx0';
		$conf->db['read'][0]['dbname'] = 'elgg0';
		$conf->db['read'][0]['dbname'] = 'elgg0';
		$conf->db['write'][0]['dbhost'] = 1;
		$conf->db['write'][0]['dbuser'] = 'user1';
		$conf->db['write'][0]['dbpass'] = 'xxxx1';
		$conf->db['write'][0]['dbname'] = 'elgg1';

		$conf->dbprefix = elgg_get_config('dbprefix');

		return new \Elgg\Database\DbConfig($conf);
	}

}