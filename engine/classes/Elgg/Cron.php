<?php

namespace Elgg;

use Elgg\Exceptions\CronException;
use Elgg\Exceptions\Exception;
use Elgg\I18n\Translator;
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

	protected array $default_intervals = [
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

	protected EventsService $events;

	protected Translator $translator;

	/**
	 * Constructor
	 *
	 * @param EventsService $events     Events service
	 * @param Translator    $translator Translator service
	 */
	public function __construct(EventsService $events, Translator $translator) {
		$this->events = $events;
		$this->translator = $translator;
	}

	/**
	 * Executes handlers for periods that have elapsed since last cron
	 *
	 * @param null|array $intervals Interval names to run (default: all cron intervals)
	 * @param bool       $force     Force cron jobs to run even they are not yet due
	 *
	 * @return Job[]
	 * @throws CronException
	 */
	public function run(array $intervals = null, bool $force = false): array {

		if (!isset($intervals)) {
			$intervals = array_keys($this->default_intervals);
		}
		
		$allowed_intervals = $this->getConfiguredIntervals();
		
		$scheduler = new Scheduler();
		$time = $this->getCurrentTime();

		foreach ($intervals as $interval) {
			if (!array_key_exists($interval, $allowed_intervals)) {
				throw new CronException("{$interval} is not a recognized cron interval");
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
	 * @param string         $interval Interval name
	 * @param null|\DateTime $time     Time of the cron initialization (default: current service time)
	 *
	 * @return void
	 */
	protected function before(string $interval, \DateTime $time = null): void {

		if (!isset($time)) {
			$time = $this->getCurrentTime();
		}

		try {
			$this->events->triggerBefore('cron', $interval, $time);
		} catch (\Throwable $t) {
			$this->getLogger()->error($t);
		}

		// give every period at least 'max_execution_time' (PHP ini setting)
		set_time_limit((int) ini_get('max_execution_time'));

		$now = new \DateTime();

		$msg = $this->translator->translate('admin:cron:started', [$interval, $time->format(DATE_RFC2822)]) . PHP_EOL;
		$msg .= $this->translator->translate('admin:cron:started:actual', [$interval, $now->format(DATE_RFC2822)]) . PHP_EOL;

		$this->cronLog('output', $interval, $msg);
	}

	/**
	 * Execute handlers attached to a specific cron interval
	 *
	 * @param string         $interval Cron interval to execute
	 * @param null|\DateTime $time     Time of cron initialization (default: current service time)
	 *
	 * @return string
	 */
	protected function execute(string $interval, \DateTime $time = null): string {

		if (!isset($time)) {
			$time = $this->getCurrentTime();
		}

		$now = new \DateTime();

		$output = [];

		$output[] = $this->translator->translate('admin:cron:started', [$interval, $time->format(DATE_RFC2822)]);
		$output[] = $this->translator->translate('admin:cron:started:actual', [$interval, $now->format(DATE_RFC2822)]);

		try {
			ob_start();
			
			$old_stdout = $this->events->triggerResults('cron', $interval, [
				'time' => $time->getTimestamp(),
				'dt' => $time,
			], '');
			
			$output[] = ob_get_clean();
			$output[] = $old_stdout;
		} catch (\Throwable $t) {
			$output[] = ob_get_clean();
			
			$this->getLogger()->error($t);
		}

		$now = new \DateTime();

		$output[] = $this->translator->translate('admin:cron:complete', [$interval, $now->format(DATE_RFC2822)]);

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
	protected function after(string $output, string $interval): void {
		$time = new \DateTime();

		$this->cronLog('output', $interval, $output);
		$this->cronLog('completion', $interval, $time->getTimestamp());

		$this->getLogger()->info($output);
		
		try {
			$this->events->triggerAfter('cron', $interval, $time);
		} catch (\Throwable $t) {
			$this->getLogger()->error($t);
		}
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
	protected function cronLog(string $setting, string $interval, string $msg = ''): void {
		$suffix = $setting ?: 'output';
		
		$fh = new \ElggFile();
		$fh->owner_guid = elgg_get_site_entity()->guid;
		$fh->setFilename("{$interval}-{$suffix}.log");
		
		try {
			$fh->open('write');
			$fh->write($msg);
		} catch (Exception $e) {
			// don't do anything
		}
		
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
	public function getLog(string $setting, string $interval): string {
		$suffix = $setting ?: 'output';
		
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
	public function getConfiguredIntervals(bool $only_names = false): array {
		$result = $this->events->triggerResults('cron:intervals', 'system', [], $this->default_intervals);
		if (!is_array($result)) {
			$this->getLogger()->warning("The event 'cron:intervals', 'system' should return an array, " . gettype($result) . ' given');
			
			$result = $this->default_intervals;
		}
		
		if ($only_names) {
			return array_keys($result);
		}
		
		return $result;
	}
}
