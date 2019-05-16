<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Wrapper for console application
 */
class Application extends \Symfony\Component\Console\Application {

	/**
	 * Configure input and output
	 *
	 * @param InputInterface  $input  Input
	 * @param OutputInterface $output Output
	 *
	 * @return void
	 */
	public function setup(InputInterface $input, OutputInterface $output) {
		$this->configureIO($input, $output);

		if (!$output->isDecorated() && !$input->hasParameterOption(['--no-ansi'], true)) {
			$output->setDecorated(true);
		}
	}
}
