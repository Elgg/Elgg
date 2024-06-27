<?php

namespace Elgg\Cli;

use Elgg\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Monolog\Formatter\FormatterInterface;

/**
 * Console handler
 */
class ErrorHandler extends AbstractProcessingHandler {
	
	const VERBOSITY_LEVEL_MAP = [
		OutputInterface::VERBOSITY_QUIET => Logger::OFF,
		OutputInterface::VERBOSITY_NORMAL => Level::Warning,
		OutputInterface::VERBOSITY_VERBOSE => Level::Notice,
		OutputInterface::VERBOSITY_VERY_VERBOSE => Level::Info,
		OutputInterface::VERBOSITY_DEBUG => Level::Debug,
	];
	
	/**
	 * @var OutputInterface
	 */
	protected $stdout;

	/**
	 * @var OutputInterface
	 */
	protected $stderr;

	/**
	 * Constructor
	 *
	 * @param OutputInterface $stdout STDOUT handler
	 * @param OutputInterface $stderr STDERR handler
	 * @param bool            $bubble Bubble severity
	 */
	public function __construct(
		OutputInterface $stdout,
		OutputInterface $stderr = null,
		$bubble = true
	) {
		$this->stdout = $stdout;
		$this->stderr = $stderr ?: $stdout;

		$verbosity = $this->stdout->getVerbosity() ?: OutputInterface::VERBOSITY_NORMAL;

		$level = self::VERBOSITY_LEVEL_MAP[$verbosity];

		parent::__construct($level, $bubble);
	}

	/**
	 * {@inheritdoc}
	 */
	public function write(LogRecord $record): void {
		$stream = $record->level->value >= Level::Error ? $this->stderr : $this->stdout;

		$stream->write($record->formatted, true);

		if ($stream instanceof StreamOutput) {
			$dumper = new CliDumper($stream->getStream());
			$cloner = new VarCloner();

			if (!empty($record->context)) {
				$dumper->dump($cloner->cloneVar($record->context));
			}

			if (!empty($record->extra)) {
				$dumper->dump($cloner->cloneVar($record->extra));
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDefaultFormatter(): FormatterInterface {
		return new ErrorFormatter();
	}

	/**
	 * {@inheritdoc}
	 */
	public function close(): void {
		$this->stdout = new NullOutput();
		$this->stderr = new NullOutput();
	}
}
