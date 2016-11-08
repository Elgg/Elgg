<?php

namespace Elgg;

use PHPUnit_Framework_BaseTestListener;
use PHPUnit_Framework_TestSuite;

class TestListener extends PHPUnit_Framework_BaseTestListener {

	/**
	 * @var string
	 */
	private $config_dir;
	
	/**
	 * Constructor
	 * 
	 * @param string $config_dir Directory containing suite-speicific config files
	 */
	public function __construct($config_dir) {
		$this->config_dir = $config_dir;
	}

	/**
	 * {@inheritdoc}
	 */
	public function startTestSuite(PHPUnit_Framework_TestSuite $suite) {

		// reset config before each suite
		global $CONFIG;
		unset($CONFIG);

		$name = $suite->getName();
		$dir = rtrim($this->config_dir, '/');

		if (file_exists("$dir/$name.php")) {
			require_once "$dir/$name.php";
		}
	}

}
