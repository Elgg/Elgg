<?php

namespace Elgg\Upgrade;

use Elgg\Application;
use Phinx\Wrapper\TextWrapper;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Elgg implementation of the Phinx Text Wrapper to ensure the correct OutputInterface
 *
 * @since 6.3
 * @internal
 */
class PhinxWrapper extends TextWrapper {
	
	/**
	 * {@inheritdoc}
	 */
	protected function executeRun(array $command): string {
		if (!Application::isCli()) {
			return parent::executeRun($command);
		}
		
		$elgg_application = Application::getInstance();
		if ($elgg_application->internal_services->config->testing_mode) {
			// PHPUnit testing
			return parent::executeRun($command);
		}
		
		// need to initialize input/output stream
		$cli_application = $elgg_application->internal_services->cli;
		
		$this->exitCode = $this->app->doRun(new ArrayInput($command), $elgg_application->internal_services->cli_output);
		
		return '';
	}
}
