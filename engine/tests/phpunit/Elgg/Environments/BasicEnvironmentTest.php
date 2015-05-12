<?php
namespace Elgg\Environments;

use Elgg\Config;

class BasicEnvironmentTest extends \PHPUnit_Framework_TestCase {

	function testBuildsProdIfNotConfigured() {
		$config = $this->getEmptyConfig();
		$env = BasicEnvironment::factory($config);

		$this->assertTrue($env->isProd());
		$this->assertEquals(BasicEnvironment::DEFAULT_NAME, $env->getName());
	}

	function testBuildsIfConfiguredByArray() {
		$config = $this->getEmptyConfig();
		$config->set(BasicEnvironment::CONFIG_KEY_FACTORY, [
			'name' => 'foo',
		]);
		$env = BasicEnvironment::factory($config);

		$this->assertTrue($env->isProd());
		$this->assertEquals('foo', $env->getName());

		$config = $this->getEmptyConfig();
		$config->set(BasicEnvironment::CONFIG_KEY_FACTORY, [
			'is_prod' => false,
		]);
		$env = BasicEnvironment::factory($config);

		$this->assertFalse($env->isProd());
		$this->assertEquals(BasicEnvironment::DEFAULT_NAME, $env->getName());
	}

	function testBuildsIfConfiguredByClosure() {
		$config = $this->getEmptyConfig();
		$config->set(BasicEnvironment::CONFIG_KEY_FACTORY, function () {
			return new BasicEnvironment('test', false);
		});
		$env = BasicEnvironment::factory($config);

		$this->assertFalse($env->isProd());
		$this->assertEquals('test', $env->getName());
	}

	/**
	 * @expectedException \ConfigurationException
	 */
	function testBuildThrowsIfBadConfig() {
		$config = $this->getEmptyConfig();
		$config->set(BasicEnvironment::CONFIG_KEY_FACTORY, 'hello');
		BasicEnvironment::factory($config);
	}

	/**
	 * @expectedException \ConfigurationException
	 */
	function testBuildThrowsIfBadFactory() {
		$config = $this->getEmptyConfig();
		$config->set(BasicEnvironment::CONFIG_KEY_FACTORY, function () {
			return 'dev';
		});
		BasicEnvironment::factory($config);
	}

	protected function getEmptyConfig() {
		$settings = (object)['Config_file' => false];
		return new Config($settings, false);
	}
}