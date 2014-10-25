<?php

class Elgg_DatabaseTest extends PHPUnit_Framework_TestCase {
	
	private $dbClass, $configClass;

	/**
	 * Inspect instances in DI container to get correct classnames for
	 * Database API.
	 */
	public function setUp() {
		// Database class
		// Cannot user _elgg_services() because ElggEntityTest replace
		// the database instance with a mock.
		$db = _elgg_create_service_provider()->db;
		$this->dbClass = get_class($db);
		
		// Config class
		$reflectionClass = new ReflectionClass($db);
		$reflectionProperty = $reflectionClass->getProperty('config');
		if (method_exists($reflectionProperty, 'setAccessible')) {
			$reflectionProperty->setAccessible(true);
			$config = $reflectionProperty->getValue($db);
			$this->configClass = get_class($config);
		} else {
			$this->configClass = 'Elgg_Database_Config';
		}
	}
	
	/**
	 * @dataProvider scriptsWithOneStatement
	 */
	public function test_runSqlScript_withOneStatement($script) {
		$db = $this->getDbMock();
		$db->expects($this->exactly(1))
			->method('updateData')
			->with($this->matchesRegularExpression("/^INSERT INTO test_sometable \(`key`\)\sVALUES \('Value(?: -- not a comment)?'\)$/"));
		$db->runSqlScript($this->getFixture($script));
	}

	public function scriptsWithOneStatement() {
		return array(
			array('one_statement.sql'),
			array('one_statement_multiline.sql'),
			array('one_statement_with_comments.sql'),
		);
	}
	
	/**
	 * @dataProvider scriptsWithMultipleStatements
	 * @todo Use @see withConsecutive() to test consecutive method calls after upgrading to PHPUnit 4.
	 */
	public function test_runSqlScript_withMultipleStatements($script) {
		// Verify that exactly three statements are executed
		$db1 = $this->getDbMock();
		$db1->expects($this->exactly(3))->method('updateData');
		$db1->runSqlScript($this->getFixture($script));

		// Verify that executed statements matches fixtures
		$db2 = $this->getDbMock();
		$this->expectExecutedStatement($db2, 0, $this->matches("INSERT INTO test_sometable (`key`) VALUES ('Value 1')"));
		$this->expectExecutedStatement($db2, 1, $this->matchesRegularExpression("/^UPDATE test_sometable\s+SET `key` = 'Value 2'\s+WHERE `key` = 'Value 1'$/"));
		$this->expectExecutedStatement($db2, 2, $this->matches("INSERT INTO test_sometable (`key`) VALUES ('Value 3')"));
		$db2->runSqlScript($this->getFixture($script));
	}
	
	public function scriptsWithMultipleStatements() {
		return array(
			array('multiple_statements.sql'),
			array('multiple_statements_with_comments.sql'),
		);
	}

	public function testFingerprintingOfCallbacks() {
		$db = $this->getDbMock();
		$prints = array();
		$uniques = 0;

		$prints[$db->fingerprintCallback('foo')] = true;
		$uniques++;

		$prints[$db->fingerprintCallback('foo::bar')] = true;
		$prints[$db->fingerprintCallback(array('foo', 'bar'))] = true;
		$uniques++;

		$obj1 = new Elgg_DatabaseTestObj();
		$prints[$db->fingerprintCallback(array($obj1, '__invoke'))] = true;
		$prints[$db->fingerprintCallback($obj1)] = true;
		$uniques++;

		$obj2 = new Elgg_DatabaseTestObj();
		$prints[$db->fingerprintCallback(array($obj2, '__invoke'))] = true;
		$uniques++;

		$this->assertEquals($uniques, count($prints));
	}

	private function getFixture($filename) {
		return dirname(__FILE__) .
			DIRECTORY_SEPARATOR . '..' .
			DIRECTORY_SEPARATOR . 'test_files' .
			DIRECTORY_SEPARATOR . 'sql' .
			DIRECTORY_SEPARATOR . $filename;
	}
	
	/**
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getDbMock()
	{
		return $this->getMock(
			$this->dbClass,
			array('updateData'),
			array(
				 new $this->configClass((object) array('dbprefix' => 'test_')),
				_elgg_services()->logger
			)
		);
	}
		
	/**
	 * @param PHPUnit_Framework_MockObject_MockObject $db
	 * @param int $index
	 * @param PHPUnit_Framework_MockObject_Matcher $matcher
	 */
	private function expectExecutedStatement($db, $index, $matcher)
	{
		$db->expects($this->at($index))->method('updateData')->with($matcher);
	}
}

class Elgg_DatabaseTestObj {
    public function __invoke() {}
}
