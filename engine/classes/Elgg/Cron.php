<?php

namespace Elgg;

use Elgg\Exceptions\CronException;
use Elgg\Traits\Loggable;
use Elgg\Traits\TimeUsing;
use GO\Job;
use GO\Scheduler;

/**
 * Cron
 *
 * @internal
 */
class Cron {

	use Loggable;
	use TimeUsing;

	/**
	 * @var array
	 */
	protected $default_intervals = [
		'minute' => '* * * * *',
		'fiveminute' => '*/5 * * * *',
		'fifteenmin' => '*/15 * * * *',
		'halfhour' => '*/30 * * * *',
		'hourly' => '0 * * * *',
		'daily' => '0 0 * * *',
		'weekly' => '0 0 * * 0',
		'monthly' => '0 0 1 * *',
		'yearly' => '0 0 1 1 *',
	];

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks  Hooks service
	 * @param EventsService      $events Events service
	 */
	public function __construct(PluginHooksService $hooks, EventsService $events) {
		$this->hooks = $hooks;
		$this->events = $events;
	}

	/**
	 * Executes handlers for periods that have elapsed since last cron
	 *
	 * @param array $intervals Interval names to run
	 * @param bool  $force     Force cron jobs to run even they are not yet due
	 *
	 * @return Job[]
	 * @throws CronException
	 */
	public function run(array $intervals = null, $force = false) {

		if (!isset($intervals)) {
			$intervals = array_keys($this->default_intervals);
		}
		
		$allowed_intervals = $this->getConfiguredIntervals();
		
		$scheduler = new Scheduler();
		$time = $this->getCurrentTime();

		foreach ($intervals as $interval) {
			if (!array_key_exists($interval, $allowed_intervals)) {
				throw new CronException("$interval is not a recognized cron interval");
			}

			$cron_interval = $force ? $allowed_intervals['minute'] : $allowed_intervals[$interval];

			$scheduler
				->call(function () use ($interval, $time) {
					return $this->execute($interval, $time);
				})
				->at($cron_interval)
				->before(function () use ($interval, $time) {
					$this->before($interval, $time);
				})
				->then(function ($output) use ($interval) {
					$this->after($output, $interval);
				});
		}

		return $scheduler->run($time);
	}

	/**
	 * Execute commands before cron interval is run
	 *
	 * @param string    $interval Interval name
	 * @param \DateTime $time     Time of the cron initialization
	 *
	 * @return void
	 */
	protected function before($interval, \DateTime $time = null) {

		if (!isset($time)) {
			$time = $this->getCurrentTime();
		}

		$this->events->triggerBefore('cron', $interval, $time);

		// give every period at least 'max_execution_time' (PHP ini setting)
		set_time_limit((int) ini_get('max_execution_time'));

		$now = new \DateTime();

		$msg = elgg_echo('admin:cron:started', [$interval, $time->format(DATE_RFC2822)]) . PHP_EOL;
		$msg .= elgg_echo('admin:cron:started:actual', [$interval, $now->format(DATE_RFC2822)]) . PHP_EOL;

		$this->log('output', $interval, $msg);
	}

	/**
	 * Execute handlers attached to a specific cron interval
	 *
	 * @param string    $interval Cron interval to execute
	 * @param \DateTime $time     Time of cron initialization
	 *
	 * @return string
	 */
	protected function execute($interval, \DateTime $time = null) {

		if (!isset($time)) {
			$time = $this->getCurrentTime();
		}

		$now = new \DateTime();

		$output = [];

		$output[] = elgg_echo('admin:cron:started', [$interval, $time->format(DATE_RFC2822)]);
		$output[] = elgg_echo('admin:cron:started:actual', [$interval, $now->format(DATE_RFC2822)]);

		ob_start();

		$old_stdout = $this->hooks->trigger('cron', $interval, [
			'time' => $time->getTimestamp(),
			'dt' => $time,
		], '');

		$output[] = ob_get_clean();
		$output[] = $old_stdout;

		$now = new \DateTime();

		$output[] = elgg_echo('admin:cron:complete', [$interval, $now->format(DATE_RFC2822)]);

		return implode(PHP_EOL, array_filter($output));
	}

	/**
	 * Printers handler result
	 *
	 * @param string $output   Output string
	 * @param string $interval Interval name
	 *
	 * @return void
	 */
	protected function after($output, $interval) {

		$time = new \DateTime();

		$this->log('output', $interval, $output);
		$this->log('completion', $interval, $time->getTimestamp());

		$this->getLogger()->info($output);

		$this->events->triggerAfter('cron', $interval, $time);
	}

	/**
	 * Log the results of a cron interval
	 *
	 * @param string $setting  'output'|'completion'
	 * @param string $interval Interval name
	 * @param string $msg      Logged message
	 *
	 * @return void
	 */
	protected function log($setting, $interval, $msg = '') {
		$suffix = $setting ? : 'output';
		
		$fh = new \ElggFile();
		$fh->owner_guid = elgg_get_site_entity()->guid;
		$fh->setFilename("{$interval}-{$suffix}.log");
		
		$fh->open('write');
		$fh->write($msg);
		$fh->close();
	}
	
	/**
	 * Get the log contents of a cron interval
	 *
	 * @param string $setting  'output'|'completion'
	 * @param string $interval Interval name
	 *
	 * @return string
	 */
	public function getLog($setting, $interval) {
		$suffix = $setting ? : 'output';
		
		$fh = new \ElggFile();
		$fh->owner_guid = elgg_get_site_entity()->guid;
		$fh->setFilename("{$interval}-{$suffix}.log");
		
		if (!$fh->exists()) {
			return '';
		}
		
		$contents = $fh->grabFile();
		if (!is_string($contents)) {
			$contents = '';
		}
		
		return $contents;
	}
	
	/**
	 * Get the cron interval configuration
	 *
	 * @param bool $only_names Only return the names of the intervals
	 *
	 * @return array
	 * @since 3.2
	 */
	public function getConfiguredIntervals(bool $only_names = false) {
		$result = $this->hooks->trigger('cron:intervals', 'system', [], $this->default_intervals);
		if (!is_array($result)) {
			$this->getLogger()->warning("The plugin hook 'cron:intervals', 'system' should return an array, " . gettype($result) . ' given');
			
			$result = $this->default_intervals;
		}
		
		if ($only_names) {
			return array_keys($result);
		}
		
		return $result;
	}
}
