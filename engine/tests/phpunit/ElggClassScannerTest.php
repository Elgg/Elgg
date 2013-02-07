<?php

class ElggClassScannerTest extends PHPUnit_Framework_TestCase {

	public function testScan() {
		$ds = DIRECTORY_SEPARATOR;
		$dir = dirname(dirname(__FILE__)) . "{$ds}test_files{$ds}class_scanner";

		if (version_compare(PHP_VERSION, '5.4', '>=')) {
			$expected = array(
				'I1_20130207' => "$dir{$ds}1.php",
				'C1_20130207' => "$dir{$ds}1.php",

				'I2_20130207' => "$dir{$ds}2.php",
				'C2_20130207' => "$dir{$ds}2.php",
				'T2_20130207' => "$dir{$ds}2.php",

				'NS3_20130207\\I3_20130207' => "$dir{$ds}3.php",
				'NS3_20130207\\C3_20130207' => "$dir{$ds}3.php",

				'NS4_20130207\\I4_20130207' => "$dir{$ds}4.php",
				'NS4_20130207\\C4_20130207' => "$dir{$ds}4.php",
				'NS4_20130207\\T4_20130207' => "$dir{$ds}4.php",
			);
		} elseif (version_compare(PHP_VERSION, '5.3', '>=')) {

			// PHP 5.3 just ignores the traits, so as long as these are in separate files, there should be
			// no problem at runtime
			$expected = array(
				'I1_20130207' => "$dir{$ds}1.php",
				'C1_20130207' => "$dir{$ds}1.php",

				'I2_20130207' => "$dir{$ds}2.php",
				'C2_20130207' => "$dir{$ds}2.php",
				//'T2_20130207' => "$dir{$ds}2.php",

				'NS3_20130207\\I3_20130207' => "$dir{$ds}3.php",
				'NS3_20130207\\C3_20130207' => "$dir{$ds}3.php",

				'NS4_20130207\\I4_20130207' => "$dir{$ds}4.php",
				'NS4_20130207\\C4_20130207' => "$dir{$ds}4.php",
				//'NS4_20130207\\T4_20130207' => "$dir{$ds}4.php",
			);
		} else {

			// PHP 5.2 also ignores the namespace declaration, making the class map think every class/interface
			// is global. If namespaced classnames collide with global classnames, at runtime the engine
			// may load the namespaced code, resulting in a fatal parse error.
			$expected = array(
				'I1_20130207' => "$dir{$ds}1.php",
				'C1_20130207' => "$dir{$ds}1.php",

				'I2_20130207' => "$dir{$ds}2.php",
				'C2_20130207' => "$dir{$ds}2.php",
				//'T2_20130207' => "$dir{$ds}2.php",

				//'NS3_20130207\\I3_20130207' => "$dir{$ds}3.php",
				//'NS3_20130207\\C3_20130207' => "$dir{$ds}3.php",
				'I3_20130207' => "$dir{$ds}3.php",
				'C3_20130207' => "$dir{$ds}3.php",

				//'NS4_20130207\\I4_20130207' => "$dir{$ds}4.php",
				//'NS4_20130207\\C4_20130207' => "$dir{$ds}4.php",
				//'NS4_20130207\\T4_20130207' => "$dir{$ds}4.php",
				'I4_20130207' => "$dir{$ds}4.php",
				'C4_20130207' => "$dir{$ds}4.php",
			);
		}

		$actual = ElggClassScanner::createMap($dir);

		ksort($expected);
		ksort($actual);

		$this->assertEquals($expected, $actual);
	}
}
