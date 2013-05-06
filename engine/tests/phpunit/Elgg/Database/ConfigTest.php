<?php

class Elgg_Database_ConfigTest extends PHPUnit_Framework_TestCase {

	public function testGetTablePrefix() {
		$CONFIG = new stdClass();
		$CONFIG->dbprefix = "foo";
		$conf = new Elgg_Database_Config($CONFIG);
		$this->assertEquals($CONFIG->dbprefix, $conf->getTablePrefix());
	}

	public function testIsDatabaseSplitNotSet() {
		$CONFIG = new stdClass();
		$conf = new Elgg_Database_Config($CONFIG);
		$this->assertFalse($conf->isDatabaseSplit());
	}

	public function testIsDatabaseSplitInSettings() {
		$CONFIG = new stdClass();
		$CONFIG->db['split'] = true;
		$conf = new Elgg_Database_Config($CONFIG);
		$this->assertTrue($conf->isDatabaseSplit());
	}

	public function testGetConnectionConfigNormalSetup() {
		$ans = array(
			'host' => 'foo',
			'user' => 'user',
			'password' => 'xxxx',
			'database' => 'elgg',
		);
		$CONFIG = new stdClass();
		$CONFIG->dbhost = $ans['host'];
		$CONFIG->dbuser = $ans['user'];
		$CONFIG->dbpass = $ans['password'];
		$CONFIG->dbname = $ans['database'];
		$conf = new Elgg_Database_Config($CONFIG);
		$this->assertEquals($ans, $conf->getConnectionConfig());
	}

	public function testGetConnectionConfigWithSingleWrite() {
		$ans = array(
			'host' => 'foo',
			'user' => 'user',
			'password' => 'xxxx',
			'database' => 'elgg',
		);
		$CONFIG = new stdClass();
		$CONFIG->db['write']['dbhost'] = $ans['host'];
		$CONFIG->db['write']['dbuser'] = $ans['user'];
		$CONFIG->db['write']['dbpass'] = $ans['password'];
		$CONFIG->db['write']['dbname'] = $ans['database'];
		$conf = new Elgg_Database_Config($CONFIG);
		$this->assertEquals($ans, $conf->getConnectionConfig(Elgg_Database_Config::WRITE));
	}

	public function testGetConnectionConfigWithMultipleRead() {
		$ans = array(
			0 => array(
				'host' => 0,
				'user' => 'user0',
				'password' => 'xxxx0',
				'database' => 'elgg0',
			),
			1 => array(
				'host' => 1,
				'user' => 'user1',
				'password' => 'xxxx1',
				'database' => 'elgg1',
			),
		);
		$CONFIG = new stdClass();
		$CONFIG->db['read'][0]['dbhost'] = $ans[0]['host'];
		$CONFIG->db['read'][0]['dbuser'] = $ans[0]['user'];
		$CONFIG->db['read'][0]['dbpass'] = $ans[0]['password'];
		$CONFIG->db['read'][0]['dbname'] = $ans[0]['database'];
		$CONFIG->db['read'][1]['dbhost'] = $ans[1]['host'];
		$CONFIG->db['read'][1]['dbuser'] = $ans[1]['user'];
		$CONFIG->db['read'][1]['dbpass'] = $ans[1]['password'];
		$CONFIG->db['read'][1]['dbname'] = $ans[1]['database'];
		$conf = new Elgg_Database_Config($CONFIG);

		$connConf = $conf->getConnectionConfig(Elgg_Database_Config::READ);
		$this->assertEquals($ans[$connConf['host']], $connConf);
	}

	// Elgg < 1.9 used objects to store the config
	public function testGetConnectionConfigWithSingleWriteOldStyle() {
		$ans = array(
			'host' => 'foo',
			'user' => 'user',
			'password' => 'xxxx',
			'database' => 'elgg',
		);
		$CONFIG = new stdClass();
		$CONFIG->db['write'] = new stdClass();
		$CONFIG->db['write']->dbhost = $ans['host'];
		$CONFIG->db['write']->dbuser = $ans['user'];
		$CONFIG->db['write']->dbpass = $ans['password'];
		$CONFIG->db['write']->dbname = $ans['database'];
		$conf = new Elgg_Database_Config($CONFIG);
		$this->assertEquals($ans, $conf->getConnectionConfig(Elgg_Database_Config::WRITE));
	}

	// Elgg < 1.9 used objects to store the config
	public function testGetConnectionConfigWithMultipleReadOldStyle() {
		$ans = array(
			0 => array(
				'host' => 0,
				'user' => 'user0',
				'password' => 'xxxx0',
				'database' => 'elgg0',
			),
			1 => array(
				'host' => 1,
				'user' => 'user1',
				'password' => 'xxxx1',
				'database' => 'elgg1',
			),
		);
		$CONFIG = new stdClass();
		$CONFIG->db['read'][0] = new stdClass();
		$CONFIG->db['read'][0]->dbhost = $ans[0]['host'];
		$CONFIG->db['read'][0]->dbuser = $ans[0]['user'];
		$CONFIG->db['read'][0]->dbpass = $ans[0]['password'];
		$CONFIG->db['read'][0]->dbname = $ans[0]['database'];
		$CONFIG->db['read'][1] = new stdClass();
		$CONFIG->db['read'][1]->dbhost = $ans[1]['host'];
		$CONFIG->db['read'][1]->dbuser = $ans[1]['user'];
		$CONFIG->db['read'][1]->dbpass = $ans[1]['password'];
		$CONFIG->db['read'][1]->dbname = $ans[1]['database'];
		$conf = new Elgg_Database_Config($CONFIG);

		$connConf = $conf->getConnectionConfig(Elgg_Database_Config::READ);
		$this->assertEquals($ans[$connConf['host']], $connConf);
	}
}
