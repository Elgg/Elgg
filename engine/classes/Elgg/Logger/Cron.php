<?php

namespace Elgg\Logger;

use Elgg\EntityDirLocator;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Project\Paths;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\TagProcessor;

/**
 * Custom logger for an individual cron interval
 *
 * @since 5.1
 */
class Cron extends \Monolog\Logger {
	
	protected const CHANNEL = 'CRON';
	
	/**
	 * @param array $params additional params:
	 *                      - (string) interval: a valid cron interval
	 *                      - (\DateTime) date: the start date of the cron
	 *
	 * @return static
	 * @throws InvalidArgumentException
	 */
	public static function factory(array $params = []): static {
		$interval = elgg_extract('interval', $params);
		$cron = _elgg_services()->cron;
		if (empty($interval) || !in_array($interval, $cron->getConfiguredIntervals(true))) {
			throw new InvalidArgumentException('Please specify a valid cron interval');
		}
		
		$filename = elgg_extract('filename', $params);
		if (empty($filename)) {
			throw new InvalidArgumentException('Please provide a log filename');
		}
		
		$logger = new static(self::CHANNEL);
		
		$handler = new StreamHandler($filename);
		
		$formatter = new ElggLogFormatter();
		$formatter->allowInlineLineBreaks();
		$formatter->ignoreEmptyContextAndExtra();
		
		$handler->setFormatter($formatter);
		
		$handler->pushProcessor(new MemoryUsageProcessor());
		$handler->pushProcessor(new MemoryPeakUsageProcessor());
		$handler->pushProcessor(new ProcessIdProcessor());
		$handler->pushProcessor(new TagProcessor([$interval]));
		$handler->pushProcessor(new PsrLogMessageProcessor());
		
		$logger->pushHandler($handler);
		
		return $logger;
	}
}
