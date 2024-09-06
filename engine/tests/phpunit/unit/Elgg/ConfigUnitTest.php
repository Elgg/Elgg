<?php

namespace Elgg;

use Elgg\Project\Paths;

class ConfigUnitTest extends \Elgg\UnitTestCase {

	public function testCanReadConfigDefaults() {
		$config = new Config();
		$this->assertTrue($config->comment_box_collapses);
	}

	public function testCanOverrideConfigDefaults() {
		$config = new Config(['comment_box_collapses' => false]);
		$this->assertFalse($config->comment_box_collapses);
	}

	public function testCanReadValuesFromConfig() {
		$config = self::getTestingConfig();

		$this->assertEquals($config->wwwroot, elgg_get_site_url());
		$this->assertEquals(realpath($config->dataroot), realpath(elgg_get_data_path()));
		$this->assertEquals(realpath($config->cacheroot), realpath(elgg_get_cache_path()));
		$this->assertEquals(realpath($config->assetroot), realpath(elgg_get_asset_path()));
		$this->assertEquals(realpath(Paths::project() . 'mod'), realpath(elgg_get_plugins_path()));

		$engine_path = dirname(dirname(dirname(dirname(dirname(__FILE__)))));

		$vendor_path = dirname($engine_path) . '/vendor/';
		if (is_dir($vendor_path)) {
			$project_path = dirname($engine_path);
		} else {
			$project_path = dirname(dirname(dirname(dirname($engine_path))));
		}
		$this->assertEquals(realpath(elgg_get_root_path()), $project_path);
	}
	
	public function testGetDefaultCookieConfig() {
		
		$config = new Config();
		
		$cookie_config = $config->getCookieConfig();
		
		$this->assertIsArray($cookie_config);
		$this->assertArrayHasKey('session', $cookie_config);
		$this->assertArrayHasKey('remember_me', $cookie_config);
		
		// session
		$session = $cookie_config['session'];
		$this->assertIsArray($session);
		
		$this->assertArrayHasKey('name', $session);
		$this->assertEquals('Elgg', $session['name']);
		
		// remember me
		$remember_me = $cookie_config['remember_me'];
		$this->assertIsArray($remember_me);
		
		$this->assertArrayHasKey('name', $remember_me);
		$this->assertEquals('elggperm', $remember_me['name']);
		
		$this->assertArrayHasKey('expire', $remember_me);
		$this->assertEquals(strtotime("+30 days"), $remember_me['expire']);
	}
	
	public function testGetCustomCookieConfig() {
		
		$custom = [
			'session' => [
				'name' => 'CustomName',
			],
			'remember_me' => [
				'name' => 'MyPerm',
				'expire' => strtotime("+300 days"),
			],
		];
		
		$config = new Config(['cookies' => $custom]);
		
		$cookie_config = $config->getCookieConfig();
		
		$this->assertIsArray($cookie_config);
		$this->assertArrayHasKey('session', $cookie_config);
		$this->assertArrayHasKey('remember_me', $cookie_config);
		
		// session
		$session = $cookie_config['session'];
		$this->assertIsArray($session);
		
		$this->assertArrayHasKey('name', $session);
		$this->assertEquals($custom['session']['name'], $session['name']);
		
		// remember me
		$remember_me = $cookie_config['remember_me'];
		$this->assertIsArray($remember_me);
		
		$this->assertArrayHasKey('name', $remember_me);
		$this->assertEquals($custom['remember_me']['name'], $remember_me['name']);
		
		$this->assertArrayHasKey('expire', $remember_me);
		$this->assertEquals($custom['remember_me']['expire'], $remember_me['expire']);
	}
}
