<?php
	/**
	 * Elgg testing framework.
	 * 
	 * This library contains an Elgg unit test framework which can be used and extended by plugins to provide
	 * functional unit tests to aid diagnostics of problems.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	/**
	 * Elgg test result object.
	 * This class is used to return the values of the test in order to generate a report.
	 */
	class ElggTestResult {
		
		/**
		 * Textual description of the test being performed (used for reporting).
		 *
		 * @var string
		 */
		private $details;
		
		/**
		 * Boolean, true if the test was executed successfully - false if not.
		 *
		 * @var bool
		 */
		private $successful;
		
		/**
		 * Any debug information for the report.
		 *
		 * @var string
		 */
		private $debug;
		
		/**
		 * Create a test result object.
		 *
		 */
		function __construct($success, $details = "", $debug = "")  
		{
			$success = (bool)$success;
			
			$this->successful = $success;
			$this->details = $details;
			$this->debug = $debug;
		}
	
		/**
		 * Factory function to generate a successful result.
		 *
		 */
		static public function CreateSuccessResult($details) { return new ElggTestResult(true, $details); }
		
		/**
		 * Factory function to generate a successful result.
		 *
		 */
		static public function CreateFailResult($details, $debug = "") { return new ElggTestResult(false, $details, $debug); }
	}
	
	/**
	 * Execute an elgg test.
	 *
	 * @param string $function The test function
	 */
	function execute_elgg_test($function)
	{
		if (is_callable($function))
			return $function();
			
		return false;
	}
	
	/**
	 * Execute all tests.
	 *
	 * @return array
	 */
	function execute_elgg_tests()
	{
		global $ELGG_TEST_REGISTRY;
		
		$report = array(); // An array to be populated with ElggTestResult objects	

		foreach ($ELGG_TEST_REGISTRY as $func => $desc)
		{
			$report[] = execute_elgg_test($func);
		}
		
		return $report;
	}
	
	/**
	 * Register an Elgg unit test.
	 *
	 * @param string $description elgg_echo description of test.
	 * @param string $function Function to execute. This function should execute the test and return a ElggTestResult object.
	 */
	function register_elgg_test($description, $function)
	{
		global $ELGG_TEST_REGISTRY;
		
		if (!$ELGG_TEST_REGISTRY) $ELGG_TEST_REGISTRY = array();
		
		$ELGG_TEST_REGISTRY[$function] = $description;
	}
	
?>