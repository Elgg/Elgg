<?php

namespace Elgg\Exceptions;

use Elgg\Project\Paths;
use Elgg\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ExceptionInstanceUnitTest extends UnitTestCase {
	
	#[DataProvider('exceptionProvider')]
	public function testImplementsInterface($exception_class) {
		if (!class_exists($exception_class)) {
			// could be an interface
			$this->markTestSkipped();
		}
		
		$exception = new $exception_class();
		$this->assertInstanceOf('\Elgg\Exceptions\ExceptionInterface', $exception);
	}
	
	public static function exceptionProvider() {
		$result = [];
		$path = Paths::elgg() . 'engine/classes/Elgg/Exceptions/';
		$path = Paths::sanitize($path);
		
		$directory = new \RecursiveDirectoryIterator($path);
		$iterator = new \RecursiveIteratorIterator($directory);
		/* @var $file_info \SplFileInfo */
		foreach ($iterator as $file_info) {
			if (!$file_info->isFile()) {
				continue;
			}
			
			$classname = $file_info->getBasename('.php');
			$file_path = Paths::sanitize($file_info->getPath());
			$sub_namespace = str_replace($path, '', $file_path);
			$sub_namespace = str_replace('/', '\\', $sub_namespace);
			
			$full_class_namespace = __NAMESPACE__ . "\\{$sub_namespace}{$classname}";
			$result[] = [$full_class_namespace];
		}
		
		return $result;
	}
}
