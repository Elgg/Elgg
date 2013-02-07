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
			$expected = array(
				'I1_20130207' => "$dir{$ds}1.php",
				'C1_20130207' => "$dir{$ds}1.php",

				'I2_20130207' => "$dir{$ds}2.php",
				'C2_20130207' => "$dir{$ds}2.php",
				//'T2_20130207' => "$dir{$ds}2.php",

				//'NS3_20130207\\I3_20130207' => "$dir{$ds}3.php",
				//'NS3_20130207\\C3_20130207' => "$dir{$ds}3.php",

				//'NS4_20130207\\I4_20130207' => "$dir{$ds}4.php",
				//'NS4_20130207\\C4_20130207' => "$dir{$ds}4.php",
				//'NS4_20130207\\T4_20130207' => "$dir{$ds}4.php",
			);
		}

		$actual = ElggClassScanner::createMap($dir);

		ksort($expected);
		ksort($actual);

		$this->assertEquals($expected, $actual);
	}
}
