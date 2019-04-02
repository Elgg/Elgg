<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI Progress reporter
 */
class Progress {

	/**
	 * @var OutputInterface
	 */
	protected $output;

	/**
	 * Constructor
	 *
	 * @param OutputInterface $output Output
	 */
	public function __construct(OutputInterface $output) {
		$this->output = $output;
	}

	/**
	 * Start a new process
	 *
	 * @param string $process Process name
	 * @param int    $max     Max number of steps in the process
	 *
	 * @return ProgressBar
	 */
	public function start($process, $max = 0) {
		$this->output->writeln($process);

		return new ProgressBar($this->output, $max);
	}

	/**
	 * Finish a process
	 *
	 * @param ProgressBar $progress Progress bar
	 * @return void
	 */
	public function finish(ProgressBar $progress) {
		$progress->finish();
		$this->output->writeln('');
	}
}
