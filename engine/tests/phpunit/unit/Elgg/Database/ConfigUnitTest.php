<?php

namespace Elgg\Database;

/**
 * @group UnitTests
 */
class ConfigUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testGetTablePrefix() {
		$obj = new \stdClass();
		$obj->dbprefix = "foo";
		$conf = new \Elgg\Database\DbConfig($obj);
		$this->assertEquals($obj->dbprefix, $conf->getTablePrefix());
	}

	public function testIsDatabaseSplitNotSet() {
		$obj = new \stdClass();
		$conf = new \Elgg\Database\DbConfig($obj);
		$this->assertFalse($conf->isDatabaseSplit());
	}

	public function testIsDatabaseSplitInSettings() {
		$obj = new \stdClass();
		$obj->db['split'] = true;
		$conf = new \Elgg\Database\DbConfig($obj);
		$this->assertTrue($conf->isDatabaseSplit());
	}

	public function testGetConnectionConfigNormalSetup() {
		$ans = array(
			'host' => 'foo',
			'port' => 3306,
			'user' => 'user',
			'password' => 'xxxx',
			'database' => 'elgg',
			'encoding' => 'utf8mb4',
			'prefix' => 'foo',
		);
		$obj = new \stdClass();
		$obj->dbhost = $ans['host'];
		$obj->dbport = $ans['port'];
		$obj->dbuser = $ans['user'];
		$obj->dbpass = $ans['password'];
		$obj->dbname = $ans['database'];
		$obj->dbencoding = $ans['encoding'];
		$obj->dbprefix = $ans['prefix'];
		$conf = new \Elgg\Database\DbConfig($obj);
		$this->assertEquals($ans, $conf->getConnectionConfig());
	}

	public function testGetConnectionConfigWithSingleWrite() {
		$ans = array(
			'host' => 'foo',
			'port' => 3306,
			'user' => 'user',
			'password' => 'xxxx',
			'database' => 'elgg',
			'encoding' => 'utf8',
			'prefix' => 'foo',
		);
		$obj = new \stdClass();
		$obj->db['write']['dbhost'] = $ans['host'];
		$obj->db['write']['dbport'] = $ans['port'];
		$obj->db['write']['dbuser'] = $ans['user'];
		$obj->db['write']['dbpass'] = $ans['password'];
		$obj->db['write']['dbname'] = $ans['database'];
		$obj->dbprefix = $ans['prefix'];
		$conf = new \Elgg\Database\DbConfig($obj);
		$this->assertEquals($ans, $conf->getConnectionConfig(\Elgg\Database\DbConfig::WRITE));
	}

	public function testGetConnectionConfigWithMultipleRead() {
		$ans = array(
			0 => array(
				'host' => 0,
				'port' => 3306,
				'user' => 'user0',
				'password' => 'xxxx0',
				'database' => 'elgg0',
				'encoding' => 'utf8',
				'prefix' => 'foo',
			),
			1 => array(
				'host' => 1,
				'port' => 3307,
				'user' => 'user1',
				'password' => 'xxxx1',
				'database' => 'elgg1',
				'encoding' => 'utf8',
				'prefix' => 'foo',
			),
		);
		$obj = new \stdClass();
		$obj->db['read'][0]['dbhost'] = $ans[0]['host'];
		$obj->db['read'][0]['dbport'] = $ans[0]['port'];
		$obj->db['read'][0]['dbuser'] = $ans[0]['user'];
		$obj->db['read'][0]['dbpass'] = $ans[0]['password'];
		$obj->db['read'][0]['dbname'] = $ans[0]['database'];
		$obj->db['read'][1]['dbhost'] = $ans[1]['host'];
		$obj->db['read'][1]['dbport'] = $ans[1]['port'];
		$obj->db['read'][1]['dbuser'] = $ans[1]['user'];
		$obj->db['read'][1]['dbpass'] = $ans[1]['password'];
		$obj->db['read'][1]['dbname'] = $ans[1]['database'];
		$obj->dbprefix = 'foo';
		$conf = new \Elgg\Database\DbConfig($obj);

		$connConf = $conf->getConnectionConfig(\Elgg\Database\DbConfig::READ);
		$this->assertEquals($ans[$connConf['host']], $connConf);
	}

	// Elgg < 1.9 used objects to store the config
	public function testGetConnectionConfigWithSingleWriteOldStyle() {
		$ans = array(
			'host' => 'foo',
			'port' => 3306,
			'user' => 'user',
			'password' => 'xxxx',
			'database' => 'elgg',
			'encoding' => 'utf8',
			'prefix' => 'foo',
		);
		$obj = new \stdClass();
		$obj->db['write'] = new \stdClass();
		$obj->db['write']->dbhost = $ans['host'];
		$obj->db['write']->dbport = $ans['port'];
		$obj->db['write']->dbuser = $ans['user'];
		$obj->db['write']->dbpass = $ans['password'];
		$obj->db['write']->dbname = $ans['database'];
		$obj->dbprefix = $ans['prefix'];
		$conf = new \Elgg\Database\DbConfig($obj);
		$this->assertEquals($ans, $conf->getConnectionConfig(\Elgg\Database\DbConfig::WRITE));
	}

	// Elgg < 1.9 used objects to store the config
	public function testGetConnectionConfigWithMultipleReadOldStyle() {
		$ans = array(
			0 => array(
				'host' => 0,
				'port' => 3306,
				'user' => 'user0',
				'password' => 'xxxx0',
				'database' => 'elgg0',
				'encoding' => 'utf8',
				'prefix' => 'foo',
			),
			1 => array(
				'host' => 1,
				'port' => 3307,
				'user' => 'user1',
				'password' => 'xxxx1',
				'database' => 'elgg1',
				'encoding' => 'utf8',
				'prefix' => 'foo',
			),
		);
		$obj = new \stdClass();
		$obj->db['read'][0] = new \stdClass();
		$obj->db['read'][0]->dbhost = $ans[0]['host'];
		$obj->db['read'][0]->dbport = $ans[0]['port'];
		$obj->db['read'][0]->dbuser = $ans[0]['user'];
		$obj->db['read'][0]->dbpass = $ans[0]['password'];
		$obj->db['read'][0]->dbname = $ans[0]['database'];
		$obj->db['read'][1] = new \stdClass();
		$obj->db['read'][1]->dbhost = $ans[1]['host'];
		$obj->db['read'][1]->dbport = $ans[1]['port'];
		$obj->db['read'][1]->dbuser = $ans[1]['user'];
		$obj->db['read'][1]->dbpass = $ans[1]['password'];
		$obj->db['read'][1]->dbname = $ans[1]['database'];
		$obj->dbprefix = 'foo';
		$conf = new \Elgg\Database\DbConfig($obj);

		$connConf = $conf->getConnectionConfig(\Elgg\Database\DbConfig::READ);
		$this->assertEquals($ans[$connConf['host']], $connConf);
	}

}
