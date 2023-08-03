<?php

namespace Elgg;

use Elgg\Exceptions\CronException;
use Elgg\I18n\DateTime;
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

	protected const LOG_FILES_TO_KEEP = 5;
	
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
			$filename = $this->getLogFilename($interval, $time);
			
			$cron_logger = \Elgg\Logger\Cron::factory([
				'interval' => $interval,
				'filename' => $filename,
			]);
			
			$scheduler
				->call(function () use ($interval, $time, $cron_logger, $filename) {
					return $this->execute($interval, $cron_logger, $filename, $time);
				})
				->at($cron_interval)
				->before(function () use ($interval, $time, $cron_logger) {
					$this->before($interval, $cron_logger, $time);
				})
				->then(function ($output) use ($interval, $cron_logger) {
					$this->after($output, $interval, $cron_logger);
				});
		}

		return $scheduler->run($time);
	}

	/**
	 * Execute commands before cron interval is run
	 *
	 * @param string            $interval    Interval name
	 * @param \Elgg\Logger\Cron $cron_logger Cron logger
	 * @param null|\DateTime    $time        Time of the cron initialization (default: current service time)
	 *
	 * @return void
	 */
	protected function before(string $interval, \Elgg\Logger\Cron $cron_logger, \DateTime $time = null): void {
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
		
		$now = new DateTime();
		
		$cron_logger->notice($this->translator->translate('admin:cron:started', [$interval, $time->format(DATE_RFC2822)]));
		$cron_logger->notice($this->translator->translate('admin:cron:started:actual', [$interval, $now->format(DATE_RFC2822)]));
	}

	/**
	 * Execute handlers attached to a specific cron interval
	 *
	 * @param string            $interval    Cron interval to execute
	 * @param \Elgg\Logger\Cron $cron_logger Cron logger
	 * @param string            $filename    Filename of the cron log
	 * @param null|\DateTime    $time        Time of cron initialization (default: current service time)
	 *
	 * @return string
	 */
	protected function execute(string $interval, \Elgg\Logger\Cron $cron_logger, string $filename, \DateTime $time = null): string {
		if (!isset($time)) {
			$time = $this->getCurrentTime();
		}
		
		try {
			ob_start();
			
			$begin_callback = function (array $params) use ($cron_logger) {
				$readable_callable = (string) elgg_extract('readable_callable', $params);
				
				$cron_logger->notice("Starting {$readable_callable}");
			};
			
			$end_callback = function (array $params) use ($cron_logger) {
				$readable_callable = (string) elgg_extract('readable_callable', $params);
				
				$cron_logger->notice("Finished {$readable_callable}");
			};
			
			$old_stdout = $this->events->triggerResults('cron', $interval, [
				'time' => $time->getTimestamp(),
				'dt' => $time,
				'logger' => $cron_logger,
			], '', [
				EventsService::OPTION_BEGIN_CALLBACK => $begin_callback,
				EventsService::OPTION_END_CALLBACK => $end_callback,
			]);
			
			$ob_output = ob_get_clean();
			
			if (!empty($ob_output)) {
				elgg_deprecated_notice('Direct output (echo, print) in a CRON event will be removed, use the provided "logger"', '5.1');
				
				$cron_logger->notice($ob_output, ['ob_output']);
			}
			
			if (!empty($old_stdout)) {
				elgg_deprecated_notice('Output in a CRON event result will be removed, use the provided "logger"', '5.1');
				
				$cron_logger->notice($old_stdout, ['event_result']);
			}
		} catch (\Throwable $t) {
			$ob_output = ob_get_clean();
			
			if (!empty($ob_output)) {
				elgg_deprecated_notice('Direct output (echo, print) in a CRON event will be removed, use the provided "logger"', '5.1');
				
				$cron_logger->notice($ob_output, ['ob_output', 'throwable']);
			}
			
			$this->getLogger()->error($t);
		}

		$now = new DateTime();

		$complete = $this->translator->translate('admin:cron:complete', [$interval, $now->format(DATE_RFC2822)]);
		$cron_logger->notice($complete);
		
		if (file_exists($filename) && is_readable($filename)) {
			return file_get_contents($filename);
		}
		
		return '';
	}

	/**
	 * Printers handler result
	 *
	 * @param string            $output      Output string
	 * @param string            $interval    Interval name
	 * @param \Elgg\Logger\Cron $cron_logger Cron logger
	 *
	 * @return void
	 */
	protected function after(string $output, string $interval, \Elgg\Logger\Cron $cron_logger): void {
		$this->getLogger()->info($output);
		
		try {
			$this->events->triggerAfter('cron', $interval, new \DateTime());
		} catch (\Throwable $t) {
			$this->getLogger()->error($t);
		}
		
		$cron_logger->close();
		$this->rotateLogs($interval);
		$this->logCompletion($interval);
	}
	
	/**
	 * Get the log files for a given cron interval
	 *
	 * The results are sorted so the newest log is the first in the array
	 *
	 * @param string $interval       cron interval
	 * @param bool   $filenames_only only return the filenames (default: false)
	 *
	 * @return array
	 */
	public function getLogs(string $interval, bool $filenames_only = false): array {
		$fh = new \ElggFile();
		$fh->owner_guid = elgg_get_site_entity()->guid;
		$fh->setFilename("cron/{$interval}/dummy.log");
		
		$dir = pathinfo($fh->getFilenameOnFilestore(), PATHINFO_DIRNAME);
		if (!is_dir($dir) || !is_readable($dir)) {
			return [];
		}
		
		$dh = new \DirectoryIterator($dir);
		$files = [];
		/* @var $file \DirectoryIterator */
		foreach ($dh as $file) {
			if ($file->isDot() || !$file->isFile()) {
				continue;
			}
			
			if ($filenames_only) {
				$files[] = $file->getFilename();
			} else {
				$files[$file->getFilename()] = file_get_contents($file->getPathname());
			}
		}
		
		if ($filenames_only) {
			natcasesort($files);
		} else {
			uksort($files, 'strnatcasecmp');
		}
		
		return array_reverse($files);
	}
	
	/**
	 * Get the time of the last completion of a cron interval
	 *
	 * @param string $interval cron interval
	 *
	 * @return null|DateTime
	 */
	public function getLastCompletion(string $interval): ?DateTime {
		$fh = new \ElggFile();
		$fh->owner_guid = elgg_get_site_entity()->guid;
		$fh->setFilename("cron/{$interval}.complete");
		
		if (!$fh->exists()) {
			return null;
		}
		
		$date = $fh->grabFile();
		if (empty($date)) {
			// how??
			return null;
		}
		
		try {
			return Values::normalizeTime($date);
		} catch (\Elgg\Exceptions\ExceptionInterface $e) {
			$this->getLogger()->warning($e);
		}
		
		return null;
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
	
	/**
	 * Get a filename to log in
	 *
	 * @param string         $interval cron interval to log
	 * @param \DateTime|null $time     start time of the cron
	 *
	 * @return string
	 */
	protected function getLogFilename(string $interval, \DateTime $time = null): string {
		if (!isset($time)) {
			$time = $this->getCurrentTime();
		}
		
		$date = $time->format(\DateTimeInterface::ATOM);
		$date = str_replace('+', 'p', $date);
		$date = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $date);
		
		$fh = new \ElggFile();
		$fh->owner_guid = elgg_get_site_entity()->guid;
		$fh->setFilename("cron/{$interval}/{$date}.log");
		
		return $fh->getFilenameOnFilestore();
	}
	
	/**
	 * Rotate the log files
	 *
	 * @param string $interval cron interval
	 *
	 * @return void
	 */
	protected function rotateLogs(string $interval): void {
		$files = $this->getLogs($interval, true);
		if (count($files) <= self::LOG_FILES_TO_KEEP) {
			return;
		}
		
		$fh = new \ElggFile();
		$fh->owner_guid = elgg_get_site_entity()->guid;
		
		while (count($files) > self::LOG_FILES_TO_KEEP) {
			$filename = array_pop($files);
			
			$fh->setFilename("cron/{$interval}/{$filename}");
			$fh->delete();
		}
	}
	
	/**
	 * Log the completion time of a cron interval
	 *
	 * @param string $interval cron interval
	 *
	 * @return void
	 */
	protected function logCompletion(string $interval): void {
		$fh = new \ElggFile();
		$fh->owner_guid = elgg_get_site_entity()->guid;
		$fh->setFilename("cron/{$interval}.complete");
		
		try {
			if ($fh->open('write') === false) {
				return;
			}
		} catch (\Elgg\Exceptions\ExceptionInterface $e) {
			$this->getLogger()->warning($e);
			return;
		}
		
		$now = new DateTime();
		$fh->write($now->format(\DateTimeInterface::ATOM));
		$fh->close();
	}
}
