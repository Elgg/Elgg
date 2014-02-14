<?php
/**
 * Tests the travis shell script
 */

class ElggTravisValidateCommitMsgTest extends ElggCommitMessageGitHookTest {
	protected $travisScript;

	public function setUp() {
		parent::setUp();

		$this->travisScript = $this->scriptsDir . 'travis/check_commit_msgs.sh';
	}

	/**
	 * Test the TRAVIS_COMMIT_RANGE env var
	 */

	/**
	 * Range with valid msgs
	 */
	public function testRange() {
		// baf2df9355a5fc63679ad1aa80f363d00a51572b..3749dda1411437bc8029b1facfe5922059a247f1
		$range = "baf2df9355a5fc63679ad1aa80f363d00a51572b...3749dda1411437bc8029b1facfe5922059a247f1";
		$cmd = "TRAVIS_COMMIT_RANGE='$range' bash {$this->travisScript}";
		$this->assertTrue($this->runCmd($cmd));

		// and with two dots
		$range = "baf2df9355a5fc63679ad1aa80f363d00a51572b..3749dda1411437bc8029b1facfe5922059a247f1";
		$cmd = "TRAVIS_COMMIT_RANGE='$range' bash {$this->travisScript}";
		$this->assertTrue($this->runCmd($cmd));
	}

	/**
	 * Range with all invalid msgs
	 */
	public function testFailingRange() {
		// 10e85ea6eff9921d5bed5d501750d660825e9304..fc62de7a6b03c3ca11f2a057db20fe2414c47d1f
		$range = '10e85ea6eff9921d5bed5d501750d660825e9304..fc62de7a6b03c3ca11f2a057db20fe2414c47d1f';
		$cmd = "TRAVIS_COMMIT_RANGE='$range' bash {$this->travisScript}";
		$this->assertFalse($this->runCmd($cmd));
	}

	/**
	 * Range with some failing msgs
	 */
	public function testSomeFailingRange() {
		// 6d3886c6b6a01399891f11b3c675fa9135786bd1..6448bb95497db21923542a10983915023c1c2d32
		$range = '6d3886c6b6a01399891f11b3c675fa9135786bd1..6448bb95497db21923542a10983915023c1c2d32';
		$cmd = "TRAVIS_COMMIT_RANGE='$range' bash {$this->travisScript}";
		$this->assertFalse($this->runCmd($cmd));
	}

	/**
	 * Test the TRAVIS_COMMIT env var
	 */

	/**
	 * Single commit with valid msg
	 */
	public function testCommit() {
		// https://github.com/Elgg/Elgg/commit/6c84d2f394530bcaceb377e734c075c227923cb7
		$sha = '6c84d2f394530bcaceb377e734c075c227923cb7';
		$cmd = "TRAVIS_COMMIT='$sha' bash {$this->travisScript}";
		$this->assertTrue($this->runCmd($cmd));
	}

	/**
	 * Single commit with invalid msg
	 */
	public function testFailingCommit() {
		// https://github.com/Elgg/Elgg/commit/8f420a15d8fe567d78dca0ee97bc71305842c995
		$sha = '8f420a15d8fe567d78dca0ee97bc71305842c995';
		$cmd = "TRAVIS_COMMIT='$sha' bash {$this->travisScript}";
		$this->assertFalse($this->runCmd($cmd));
	}

	/**
	 * Test PR with all valid msgs
	 */
	public function testPrMerge() {
		// https://github.com/Elgg/Elgg/commit/9a54813f36ba019e11561ba4f685021a0f4dbf9a
		$sha = '9a54813f36ba019e11561ba4f685021a0f4dbf9a';
		$cmd = "TRAVIS_COMMIT='$sha' bash {$this->travisScript}";
		$this->assertTrue($this->runCmd($cmd));
	}

	/**
	 * PR with invalid messages
	 */
	public function testFailingPrMerge() {
		// https://github.com/Elgg/Elgg/commit/cfc2a3fb9e97488e36f5a5771c816fff90e3691f
		$sha = 'cfc2a3fb9e97488e36f5a5771c816fff90e3691f';
		$cmd = "TRAVIS_COMMIT='$sha' bash {$this->travisScript}";
		$this->assertFalse($this->runCmd($cmd));
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
		$this->assertTrue($this->runCmd($cmd));
	}

	/**
	 * Range
	 */
	public function testRangeAsArg() {
		$range = "baf2df9355a5fc63679ad1aa80f363d00a51572b..3749dda1411437bc8029b1facfe5922059a247f1";
		$cmd = "bash {$this->travisScript} $range";
		$this->assertTrue($this->runCmd($cmd));
	}

	public function testPrAsArg() {
		$sha = '9a54813f36ba019e11561ba4f685021a0f4dbf9a';
		$cmd = "bash {$this->travisScript} $sha";
		$this->assertTrue($this->runCmd($cmd));
	}
}