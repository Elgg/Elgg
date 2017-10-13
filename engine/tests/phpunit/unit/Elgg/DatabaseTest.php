<?php

/**
 * @group UnitTests
 * @group Database
 */
class Elgg_DatabaseUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

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
		return [
			['one_statement.sql'],
			['one_statement_multiline.sql'],
			['one_statement_with_comments.sql'],
		];
	}

	/**
	 * @dataProvider scriptsWithMultipleStatements
	 * @todo         Use @see withConsecutive() to test consecutive method calls after upgrading to PHPUnit 4.
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
		return [
			['multiple_statements.sql'],
			['multiple_statements_with_comments.sql'],
		];
	}

	public function testFingerprintingOfCallbacks() {
		$db = $this->getDbMock();
		
		$reflection_method = new ReflectionMethod($db, 'fingerprintCallback');
		$reflection_method->setAccessible(true);
		
		$prints = [];
		$uniques = 0;

		$prints[$reflection_method->invoke($db, 'foo')] = true;
		$uniques++;

		$prints[$reflection_method->invoke($db, 'foo::bar')] = true;
		$prints[$reflection_method->invoke($db, [
			'foo',
			'bar'
		])] = true;
		$uniques++;

		$obj1 = new Elgg_DatabaseTestObj();
		$prints[$reflection_method->invoke($db, [
			$obj1,
			'__invoke'
		])] = true;
		$prints[$reflection_method->invoke($db, $obj1)] = true;
		$uniques++;

		$obj2 = new Elgg_DatabaseTestObj();
		$prints[$reflection_method->invoke($db, [
			$obj2,
			'__invoke'
		])] = true;
		$uniques++;

		$this->assertEquals($uniques, count($prints));
	}

	/**
	 * @expectedException \RuntimeException
	 * @expectedExceptionMessage $callback must be a callable function. Given blorg!
	 */
	public function testInvalidCallbacksThrow() {
		$db = $this->getDbMock();
		$db->getData("SELECT 1", 'blorg!');
	}

	private function getFixture($filename) {
		return $this->normalizeTestFilePath("sql/$filename");
	}

	/**
	 * @return \Elgg\Database|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getDbMock() {
		return $this->getMockBuilder(\Elgg\Database::class)
			->setMethods(['updateData'])
			->setConstructorArgs([
				new \Elgg\Database\DbConfig((object) ['dbprefix' => 'test_']),
				_elgg_services()->logger
			])
			->getMock();
	}

	/**
	 * @param PHPUnit_Framework_MockObject_MockObject $db
	 * @param int                                     $index
	 * @param PHPUnit_Framework_MockObject_Matcher    $matcher
	 */
	private function expectExecutedStatement($db, $index, $matcher) {
		$db->expects($this->at($index))->method('updateData')->with($matcher);
	}

}

class Elgg_DatabaseTestObj {

	public function __invoke() {

	}

}
