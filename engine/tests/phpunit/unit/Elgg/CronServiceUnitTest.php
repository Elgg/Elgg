<?php

namespace Elgg;

use Elgg\Exceptions\CronException;

class CronServiceUnitTest extends UnitTestCase {

	/**
	 * @var Cron
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		elgg_register_route('cron', [
			'path' => '/cron/{segments}',
			'controller' => \Elgg\Controllers\Cron::class,
			'requirements' => [
				'segments' => '.+',
			],
		]);
		
		$this->service = _elgg_services()->cron;
	}

	public function testCanRunCron() {

		$dt = new \DateTime('2017-1-1 0:00:00');

		$this->service->setCurrentTime($dt);

		$jobs = $this->service->run();

		$intervals = $this->service->getConfiguredIntervals();

		$this->assertEquals(count($intervals), count($jobs));

		foreach ($jobs as $job) {
			$this->assertNotEmpty($job->getOutput());
		}
	}

	public function testCanRunCronForSpecificIntervals() {

		$dt = new \DateTime('2017-1-1 0:00:00');

		$this->service->setCurrentTime($dt);

		$intervals = ['monthly', 'yearly'];

		$jobs = $this->service->run($intervals);

		$this->assertEquals(count($intervals), count($jobs));

		foreach ($jobs as $job) {
			$this->assertNotEmpty($job->getOutput());
		}

	}

	public function testCanAddCronHandler() {

		$calls = 0;
		$handler = function() use (&$calls) {
			$calls++;
			echo 'Success';
		};

		elgg_register_event_handler('cron', 'yearly', $handler);

		$dt = new \DateTime('2017-1-1 0:00:00');

		$this->service->setCurrentTime($dt);

		$jobs = $this->service->run(['yearly']);

		$this->assertEquals(1, count($jobs));

		foreach ($jobs as $job) {
			$this->assertNotEmpty($job->getOutput());
		}

		elgg_unregister_event_handler('cron', 'yearly', $handler);
	}

	public function testCanExecuteCronFromPageHandler() {
		_elgg_services()->config->security_protect_cron = false;
		
		$calls = 0;
		$dt = new \DateTime('2017-1-1 0:00:00');
		
		$handler = function(\Elgg\Event $event) use (&$calls, $dt) {
			$calls++;
			$this->assertEquals($dt->getTimestamp(), $event->getParam('time'));
			$this->assertEquals($dt, $event->getParam('dt'));
		};

		elgg_register_event_handler('cron', 'yearly', $handler);

		$this->service->setCurrentTime($dt);

		ob_start();
		$request = $this->prepareHttpRequest('cron/run');
		_elgg_services()->router->route($request);
		_elgg_services()->responseFactory->getSentResponse();
		ob_get_clean();

		$this->assertEquals(1, $calls);

		elgg_unregister_event_handler('cron', 'yearly', $handler);
	}

	public function testCanExecuteCronFromPageHandlerForInterval() {
		_elgg_services()->config->security_protect_cron = false;

		$calls = 0;
		$dt = new \DateTime('2017-1-1 0:00:00');
		
		$handler = function(\Elgg\Event $event) use (&$calls, $dt) {
			$calls++;
			$this->assertEquals($dt->getTimestamp(), $event->getParam('time'));
			$this->assertEquals($dt, $event->getParam('dt'));
		};

		elgg_register_event_handler('cron', 'yearly', $handler);

		$this->service->setCurrentTime($dt);

		ob_start();
		$request = $this->prepareHttpRequest('cron/yearly');
		_elgg_services()->router->route($request);
		_elgg_services()->responseFactory->getSentResponse();
		ob_get_clean();
		
		$this->assertEquals(1, $calls);
		
		elgg_unregister_event_handler('cron', 'yearly', $handler);
	}

	public function testThrowsOnInvalidInterval() {
		$this->expectException(CronException::class);
		$this->service->run(['foo']);
	}
	
	public function testCanRegisterCustomInterval() {
		
		// check if it can get registered
		$custom_interval = function(\Elgg\Event $event) {
			$intervals = $event->getValue();
			$intervals['foo'] = '30 16 * * *';
			
			return $intervals;
		};
		
		elgg_register_event_handler('cron:intervals', 'system', $custom_interval);
		
		$intervals = $this->service->getConfiguredIntervals();
		
		$this->assertArrayHasKey('foo', $intervals);
		$this->assertEquals('30 16 * * *', $intervals['foo']);
		
		// check if it can get called
		$calls = 0;
		$handler = function() use (&$calls) {
			$calls++;
			echo 'Success';
		};
		
		elgg_register_event_handler('cron', 'foo', $handler);
		
		$dt = new \DateTime('2019-1-14 16:30:00');
		
		$this->service->setCurrentTime($dt);
		
		$jobs = $this->service->run(['foo']);
		
		$this->assertCount(1, $jobs);
		$this->assertEquals(1, $calls);
		
		// cleanup
		elgg_unregister_event_handler('cron', 'foo', $handler);
		elgg_unregister_event_handler('cron:intervals', 'system', $custom_interval);
	}
}
