<?php

namespace Elgg\Cli;

use Elgg\Exceptions\Configuration\InstallationException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * elgg-cli install [--config]
 */
class InstallCommand extends BaseCommand {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('install')
			->setDescription('Install Elgg using a configuration file or interactive questions')
			->addOption('config', 'c', InputOption::VALUE_OPTIONAL,
				'Path to php file that returns an array with installation configuration'
			)
			->addOption('no-plugins', null, InputOption::VALUE_NONE,
				'Prevents activation of bundled plugins'
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$this->input = $input;
		$this->output = $output;

		$config = $this->option('config');
		if ($config && file_exists(realpath($config))) {
			$params = include $config;
		} else {
			$params = [
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
				'dbhost' => $this->ask('Enter database host: ', 'localhost'),
				'dbport' => $this->ask('Enter database port: ', '3306'),
				'dbuser' => $this->ask('Enter database username: '),
				'dbpassword' => $this->ask('Enter database password: ', null, true),
				'dbname' => $this->ask('Enter database name: '),
				'dbprefix' => $this->ask('Enter database prefix (for example: elgg_): ', '', false, false),
				/**
				 * Site settings
				 */
				'sitename' => $this->ask('Enter site name: '),
				'siteemail' => $this->ask('Enter site email: ', $email),
				'wwwroot' => $this->ask('Enter site URL (including protocol http|https and a trailing /): '),
				'dataroot' => $this->ask('Enter data directory path: '),
				'timezone' => 'UTC',
			];
		}
		
		if ($this->option('no-plugins')) {
			$params['activate_plugins'] = false;
		}
		
		try {
			$installer = new \ElggInstaller();
			$htaccess = !is_file(\Elgg\Project\Paths::project() . '.htaccess');
			$installer->batchInstall($params, $htaccess);
		} catch (InstallationException $ex) {
			$this->dumpRegisters();
			$this->write($ex->getMessage(), 'error');

			return self::FAILURE;
		}

		\Elgg\Application::start();

		$release = elgg_get_release();

		$this->write("Elgg {$release} install successful");
		$this->write('wwwroot: ' . elgg_get_site_url());
		$this->write('dataroot: ' . elgg_get_data_path());
		$this->write('cacheroot: ' . elgg_get_cache_path());
		$this->write('assetroot: ' . elgg_get_asset_path());

		return self::SUCCESS;
	}
}
