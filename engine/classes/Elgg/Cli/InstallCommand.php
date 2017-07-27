<?php

namespace Elgg\Cli;

use ElggInstaller;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * install CLI command
 */
class InstallCommand extends \Symfony\Component\Console\Command\Command {

	use ConsoleInteractions;

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('install')->setDescription('Install Elgg');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {

		$this->input = $input;
		$this->output = $output;

		$params = array(
			/**
			 * Admin account
			 */
			'displayname' => 'Administrator',
			'username' => $this->ask('Enter admin username: ', 'admin'),
			'password' => $this->ask('Enter admin password: ', null, true),
			'email' => $email = $this->ask('Enter admin email: '),
			/**
			 * Database parameters
			 */
			'dbuser' => $this->ask('Enter database username: '),
			'dbpassword' => $this->ask('Enter database password: ', null, true),
			'dbname' => $this->ask('Enter database name: '),
			'dbprefix' => $this->ask('Enter database prefix [elgg_]: ', 'elgg_'),
			/**
			 * Site settings
			 */
			'sitename' => $this->ask('Enter site name: '),
			'siteemail' => $this->ask("Enter site email [$email]: ", $email),
			'wwwroot' => $this->ask('Enter site URL:'),
			'dataroot' => $this->ask('Enter data directory path: '),
			'timezone' => 'UTC'
		);

		global $CONFIG;
		$CONFIG = new \stdClass();
		$CONFIG->system_cache_enabled = false;

		foreach ($params as $key => $value) {
			$CONFIG->$key = $value;
		}

		$installer = new ElggInstaller();
		$htaccess = !is_file(\Elgg\Filesystem\Directory\Local::root()->getPath('.htaccess'));
		$installer->batchInstall($params, $htaccess);

		$this->write('Installation is successful');

	}

}
