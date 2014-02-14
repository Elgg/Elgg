<?php
/**
 * Tests the commit message validation shell script used by the git hook and travis
 */

class ElggCommitMessageGitHookTest extends PHPUnit_Framework_TestCase {
	protected $scriptsDir;
	protected $filesDir;
	protected $validateScript;

	protected $validTypes = array(
		'feature',
		'fix',
		'docs',
		'chore',
		'perf',
		'security'
	);

	public function setUp() {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$this->markTestSkipped('Can only test in *nix envs.');
		}

		$this->scriptsDir = dirname(dirname(dirname(dirname(__FILE__)))) . '/.scripts/';
		$this->filesDir = dirname(__FILE__) . '/test_files/commit_messages/';
		$this->validateScript = "php {$this->scriptsDir}validate_commit_msg.php";

		parent::setUp();
	}
	
	/**
	 * Test failures for missing input
	 */
	public function testInvalidInputs() {
		// have to pass an empty arg because it looks for stdin
		$cmd = "$this->validateScript ''";
		$this->assertFalse($this->runCmd($cmd));

		$cmd = "$this->validateScript /dev/null";
		$this->assertFalse($this->runCmd($cmd));

		$cmd = "echo '' | $this->validateScript";
		$this->assertFalse($this->runCmd($cmd));
	}

	public function testInvalidMessage() {
		$cmd = "$this->validateScript {$this->filesDir}invalid_format.txt";
		$this->assertFalse($this->runCmd($cmd));
	}

	public function testFile() {
		$cmd = "$this->validateScript {$this->filesDir}valid.txt";
		$this->assertTrue($this->runCmd($cmd));
	}
	
	public function testPipe() {
		$msg = escapeshellarg(file_get_contents("{$this->filesDir}valid.txt"));
		$cmd = "echo $msg | $this->validateScript";
		$this->assertTrue($this->runCmd($cmd));
	}

	public function testArg() {
		$msg = escapeshellarg(file_get_contents("{$this->filesDir}valid.txt"));
		$cmd = "$this->validateScript $msg";
		$this->assertTrue($this->runCmd($cmd));
	}

	/**
	 * Executes a command and returns true if the cmd
	 * exited with 0.
	 * 
	 * @param string $cmd
	 */
	protected function runCmd($cmd, $return_output = false) {
		$output = array();
		$exit = 0;
		exec($cmd, $output, $exit);

		if ($return_output) {
			return implode("\n", $output);
		}

		return $exit > 0 ? false : true;
	}
}