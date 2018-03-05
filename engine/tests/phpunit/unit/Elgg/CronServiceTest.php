<?php

namespace phpunit\unit\Elgg;

use Elgg\Cron;
use Elgg\UnitTestCase;

/**
 * @group Cron
 */
class CronServiceTest extends UnitTestCase {

	public function up() {
		elgg_register_route('cron', [
			'path' => '/cron/{segments}',
			'handler' => '_elgg_cron_page_handler',
			'requirements' => [
				'segments' => '.+',
			],
		]);
	}

	public function down() {

	}

	public function testCanRunCron() {

		$dt = new \DateTime('2017-1-1 0:00:00');

		_elgg_services()->cron->setCurrentTime($dt);

		$jobs = _elgg_services()->cron->run();

		$intervals = array_keys(Cron::$intervals);

		$this->assertEquals(count($intervals), count($jobs));

		foreach ($jobs as $job) {
			$this->assertNotEmpty($job->getOutput());
		}

	}

	public function testCanRunCronForSpecificIntervals() {

		$dt = new \DateTime('2017-1-1 0:00:00');

		_elgg_services()->cron->setCurrentTime($dt);

		$intervals = ['monthly', 'yearly'];

		$jobs = _elgg_services()->cron->run($intervals);

		$this->assertEquals(count($intervals), count($jobs));

		foreach ($jobs as $job) {
			$this->assertNotEmpty($job->getOutput());
		}

	}

	public function testCanAddCronHandler() {

		$handler = function() use (&$calls) {
			$calls++;
			echo 'Success';
		};

		elgg_register_plugin_hook_handler('cron', 'yearly', $handler);

		$dt = new \DateTime('2017-1-1 0:00:00');

		_elgg_services()->cron->setCurrentTime($dt);

		$jobs = _elgg_services()->cron->run(['yearly']);

		$this->assertEquals(1, count($jobs));

		foreach ($jobs as $job) {
			$this->assertNotEmpty($job->getOutput());
		}

		elgg_unregister_plugin_hook_handler('cron', 'yearly', $handler);
	}

	public function testCanExecuteCronFromPageHandler() {
		_elgg_cron_init();

		_elgg_config()->security_protect_cron = false;

		$dt = new \DateTime('2017-1-1 0:00:00');

		$handler = function(\Elgg\Hook $hook) use (&$calls, $dt) {
			$calls++;
			$this->assertEquals($dt->getTimestamp(), $hook->getParam('time'));
			$this->assertEquals($dt, $hook->getParam('dt'));
			echo 'Cron hook handler called';
		};

		elgg_register_plugin_hook_handler('cron', 'yearly', $handler);

		_elgg_services()->cron->setCurrentTime($dt);

		ob_start();
		$request = $this->prepareHttpRequest('cron/run');
		_elgg_services()->router->route($request);
		$response = _elgg_services()->responseFactory->getSentResponse();
		ob_get_clean();

		$this->assertRegExp('/Cron hook handler called/im', $response->getContent());

		elgg_unregister_plugin_hook_handler('cron', 'yearly', $handler);
	}

	public function testCanExecuteCronFromPageHandlerForInterval() {
		_elgg_cron_init();

		_elgg_config()->security_protect_cron = false;

		$dt = new \DateTime('2017-1-1 0:00:00');

		$handler = function(\Elgg\Hook $hook) use (&$calls, $dt) {
			$calls++;
			$this->assertEquals($dt->getTimestamp(), $hook->getParam('time'));
			$this->assertEquals($dt, $hook->getParam('dt'));
			echo 'Cron hook handler called';
		};

		elgg_register_plugin_hook_handler('cron', 'yearly', $handler);

		_elgg_services()->cron->setCurrentTime($dt);

		ob_start();
		$request = $this->prepareHttpRequest('cron/yearly');
		_elgg_services()->router->route($request);
		$response = _elgg_services()->responseFactory->getSentResponse();
		ob_get_clean();

		$this->assertRegExp('/Cron hook handler called/im', $response->getContent());

		elgg_unregister_plugin_hook_handler('cron', 'yearly', $handler);
	}

	/**
	 * @expectedException \CronException
	 */
	public function testThrowsOnInvalidInterval() {
		_elgg_services()->cron->run(['foo']);
	}


}