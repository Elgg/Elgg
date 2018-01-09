<?php

namespace Elgg\Printer;

use Elgg\Logger;
use Elgg\Printer;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Cli Printer
 *
 * @access private
 */
class CliPrinter implements Printer {

	/**
	 * {@inheritdoc}
	 */
	public function write($data, $level) {
		if (!is_string($data)) {
			VarDumper::dump($data);
			return;
		}

		$output = new ConsoleOutput(Logger::$verbosity ? : ConsoleOutput::VERBOSITY_NORMAL);
		$formatter = new FormatterHelper();

		switch ($level) {
			case Logger::ERROR :
				$message = $formatter->formatBlock($data, 'error');
				$output->writeln($message);
				break;

			case Logger::WARNING :
				$message = $formatter->formatBlock($data, 'error');
				$output->writeln($message, ConsoleOutput::VERBOSITY_VERBOSE);
				break;

			case Logger::NOTICE :
				$message = $formatter->formatBlock($data, 'info');
				$output->writeln($message, ConsoleOutput::VERBOSITY_VERY_VERBOSE);
				break;

			default :
				$message = $formatter->formatBlock($data, 'info');
				$output->writeln($message, ConsoleOutput::VERBOSITY_DEBUG);
				break;
		}
	}
}