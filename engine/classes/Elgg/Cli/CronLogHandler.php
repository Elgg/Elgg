<?php

namespace Elgg\Cli;

use Elgg\Application as ElggApplication;
use Elgg\Exceptions\Exception;
use Elgg\Logger\ElggLogFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Additional output handler for the \Elgg\Logger\Cron
 * which outputs to the stdout
 *
 * @since 6.3
 * @internal
 */
class CronLogHandler extends AbstractProcessingHandler {
	
	protected ?OutputInterface $stdout;
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(bool $bubble = true) {
		if (!ElggApplication::isCli()) {
			throw new Exception(__CLASS__ . ' can only be used during CLI');
		}
		
		$this->stdout = _elgg_services()->cli_output;
		
		$level = Level::Emergency;
		if ($this->stdout->getVerbosity() !== OutputInterface::VERBOSITY_QUIET) {
			$level = Level::Debug;
		}
		
		parent::__construct($level, $bubble);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function write(LogRecord $record): void {
		$this->stdout?->write($record->formatted);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getDefaultFormatter(): FormatterInterface {
		$formatter = new ElggLogFormatter();
		$formatter->allowInlineLineBreaks();
		$formatter->ignoreEmptyContextAndExtra();
		
		return $formatter;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function close(): void {
		$this->stdout = new NullOutput();
	}
}
