<?php

namespace Elgg;

/**
 * Tests the travis shell script
 *
 * @group UnitTests
 */
class TravisValidateCommitMsgTest extends CommitMessageGitHookUnitTest {

	protected $travisScript;

	public function up() {
		$this->travisScript = $this->scriptsDir . 'travis/check_commit_msgs.sh';

		$this->markTestSkipped('Testing against particular SHAs is too flaky.');
	}

	public function down() {

	}

	/**
	 * Test the TRAVIS_COMMIT_RANGE env var
	 */

	/**
	 * Range with valid msgs
	 */
	public function testRange() {
		// baf2df9355a5fc63679ad1aa80f363d00a51572b..3749dda1411437bc8029b1facfe5922059a247f1
		$cmd = "bash {$this->travisScript}";
		$result = $this->runCmd($cmd, $output, array(
			'TRAVIS_COMMIT_RANGE' => "baf2df9355a5fc63679ad1aa80f363d00a51572b...3749dda1411437bc8029b1facfe5922059a247f1",
		));
		$this->assertTrue($result, $output);

		// and with two dots
		$cmd = "bash {$this->travisScript}";
		$result = $this->runCmd($cmd, $output, array(
			'TRAVIS_COMMIT_RANGE' => "baf2df9355a5fc63679ad1aa80f363d00a51572b..3749dda1411437bc8029b1facfe5922059a247f1",
		));
		$this->assertTrue($result, $output);
	}

	/**
	 * Range with all invalid msgs
	 */
	public function testFailingRange() {
		// 10e85ea6eff9921d5bed5d501750d660825e9304..fc62de7a6b03c3ca11f2a057db20fe2414c47d1f
		$cmd = "bash {$this->travisScript}";
		$result = $this->runCmd($cmd, $output, array(
			'TRAVIS_COMMIT_RANGE' => '10e85ea6eff9921d5bed5d501750d660825e9304..fc62de7a6b03c3ca11f2a057db20fe2414c47d1f',
		));
		$this->assertFalse($result, $output);
	}

	/**
	 * Range with some failing msgs
	 */
	public function testSomeFailingRange() {
		// 6d3886c6b6a01399891f11b3c675fa9135786bd1..6448bb95497db21923542a10983915023c1c2d32
		$cmd = "bash {$this->travisScript}";
		$result = $this->runCmd($cmd, $output, array(
			'TRAVIS_COMMIT_RANGE' => '6d3886c6b6a01399891f11b3c675fa9135786bd1..6448bb95497db21923542a10983915023c1c2d32',
		));
		$this->assertFalse($result, $output);
	}

	/**
	 * Test the TRAVIS_COMMIT env var
	 */

	/**
	 * Single commit with valid msg
	 */
	public function testCommit() {
		// https://github.com/Elgg/Elgg/commit/6c84d2f394530bcaceb377e734c075c227923cb7
		$cmd = "bash {$this->travisScript}";
		$result = $this->runCmd($cmd, $output, array(
			'TRAVIS_COMMIT' => '6c84d2f394530bcaceb377e734c075c227923cb7',
		));
		$this->assertTrue($result, $output);
	}

	/**
	 * Single commit with invalid msg
	 */
	public function testFailingCommit() {
		// https://github.com/Elgg/Elgg/commit/8f420a15d8fe567d78dca0ee97bc71305842c995
		$cmd = "bash {$this->travisScript}";
		$result = $this->runCmd($cmd, $output, array(
			'TRAVIS_COMMIT' => '8f420a15d8fe567d78dca0ee97bc71305842c995',
		));
		$this->assertFalse($result, $output);
	}

	/**
	 * Test PR with all valid msgs
	 */
	public function testPrMerge() {
		// https://github.com/Elgg/Elgg/commit/9a54813f36ba019e11561ba4f685021a0f4dbf9a
		$cmd = "bash {$this->travisScript}";
		$result = $this->runCmd($cmd, $output, array(
			'TRAVIS_COMMIT' => '9a54813f36ba019e11561ba4f685021a0f4dbf9a',
		));
		$this->assertTrue($result, $output);
	}

	/**
	 * PR with invalid messages
	 */
	public function testFailingPrMerge() {
		// https://github.com/Elgg/Elgg/commit/cfc2a3fb9e97488e36f5a5771c816fff90e3691f
		$cmd = "bash {$this->travisScript}";
		$result = $this->runCmd($cmd, $output, array(
			'TRAVIS_COMMIT' => 'cfc2a3fb9e97488e36f5a5771c816fff90e3691f',
		));
		$this->assertFalse($result, $output);
	}

	/**
	 * Test passing commits as an argument
	 */

	/**
	 * Single commit
	 */
	public function testSHAAsArg() {
		$sha = '6c84d2f394530bcaceb377e734c075c227923cb7';
		$cmd = "bash {$this->travisScript} $sha";
		$result = $this->runCmd($cmd, $output);
		$this->assertTrue($result, $output);
	}

	/**
	 * Range
	 */
	public function testRangeAsArg() {
		$range = "baf2df9355a5fc63679ad1aa80f363d00a51572b..3749dda1411437bc8029b1facfe5922059a247f1";
		$cmd = "bash {$this->travisScript} $range";
		$result = $this->runCmd($cmd, $output);
		$this->assertTrue($result, $output);
	}

	public function testPrAsArg() {
		$sha = '9a54813f36ba019e11561ba4f685021a0f4dbf9a';
		$cmd = "bash {$this->travisScript} $sha";
		$result = $this->runCmd($cmd, $output);
		$this->assertTrue($result, $output);
	}

}
