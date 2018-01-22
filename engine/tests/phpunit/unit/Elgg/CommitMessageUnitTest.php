<?php

namespace Elgg;

/**
 * Tests the commit message validator
 *
 * @group UnitTests
 */
class CommitMessageUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function assertInvalidCommitMessages(array $msgs) {
		$msg = new CommitMessage();

		foreach ($msgs as $text) {
			$msg->setMsg($text);
			$this->assertFalse($msg->isValidFormat(), $text);
		}
	}

	public function testRejectsMessagesWithoutSummary() {
		$this->assertInvalidCommitMessages(array(
			'chore(test):',
			'chore(test): ',
			"chore(test):\n",
		));
	}

	public function testRejectsMessagesWithoutType() {
		$this->assertInvalidCommitMessages(array(
			'A bad commit message',
		));
	}

	public function testRejectsMessagesWithoutComponent() {
		$this->assertInvalidCommitMessages(array(
			'chore: Summary',
			'chore(): Summary',
			'chore(test):Summary',
		));
	}

	public function assertIgnoreCommitMessages(array $ignored) {
		foreach ($ignored as $msg) {
			$msg = new CommitMessage($msg);
			$this->assertTrue($msg->shouldIgnore(), $msg);
		}
	}

	public function testShouldIgnoreMerges() {
		$this->assertIgnoreCommitMessages(array(
			'Merge pull request',
			'Merge abc123 into def456',
			"Merge pull request abc123\nBut has other stuff, too",
			'Merge release 1.8.18 into master.',
		));
	}

	public function testShouldIgnoreReverts() {
		$this->assertIgnoreCommitMessages(array(
			'Revert "fix(amd): removed elgg_require_js for backwards compatibility"

			This reverts commit 76584089bee2b3246c736edb6b250e149acf906f.

			Conflicts:
				engine/lib/views.php'
		));
	}

	public function testCanParseMessagesWithoutBody() {
		$text = "chore(test): Summary";

		$msg = new CommitMessage($text);

		$this->assertTrue($msg->isValidFormat());
		$this->assertSame('chore', $msg->getPart('type'));
		$this->assertSame('test', $msg->getPart('component'));
		$this->assertSame('Summary', $msg->getPart('summary'));
		$this->assertSame('', $msg->getPart('body'));
	}

	public function testCanParseMessagesWithOneLineBody() {
		$text = "chore(test): Summary\nOptional body";
		$msg = new CommitMessage($text);

		$this->assertTrue($msg->isValidFormat());
		$this->assertSame('chore', $msg->getPart('type'));
		$this->assertSame('test', $msg->getPart('component'));
		$this->assertSame('Summary', $msg->getPart('summary'));
		$this->assertSame('Optional body', $msg->getPart('body'));
	}

	public function testCanParseMessagesWithAnExtendedBody() {
		$title = "chore(test): Summary";

		$body = <<<___MSG
Optional body

Fixes #123, #456
Refs #789
___MSG;
		$body = $this->convertLineEndings($body);
		$text = "$title\n$body";
		
		$msg = new CommitMessage($text);

		$this->assertTrue($msg->isValidFormat());
		$this->assertSame('chore', $msg->getPart('type'));
		$this->assertSame('test', $msg->getPart('component'));
		$this->assertSame('Summary', $msg->getPart('summary'));
		$this->assertSame($body, $msg->getPart('body'));
	}

	public function testIsValidLineLengthRejectsLinesOverTheMaxLineLength() {
		$text = "chore(test): But with long line";
		$msg = new CommitMessage();
		$msg->setMaxLineLength(15);
		$msg->setMsg($text);
		$this->assertFalse($msg->isValidLineLength());
	}

	public function testFindLengthyLinesFindsLinesOverTheMaxLineLength() {
		$text = "This text is 33 characters long.";
		$this->assertSame(array(1), CommitMessage::findLengthyLines($text, 30));

		$text2 = 'This line is only 22.';
		$this->assertSame(array(), CommitMessage::findLengthyLines($text2, 30));

		$text3 = <<<___TEXT
This has multiple lines.
Some of which are not really very long at all.
Some are.
___TEXT;
		$text3 = $this->convertLineEndings($text3);

		$this->assertSame(array(2), CommitMessage::findLengthyLines($text3, 30));
	}

	public function testGetLengthyLinesFindsLinesOverTheMaxLineLength() {
		$text = <<<___MSG
chore(test): But with long line

The long line is down here. This line is much longer than the other.
And this one is short.
But here we go again with another long line.
___MSG;
		$text = $this->convertLineEndings($text);
		
		$msg = new CommitMessage();
		$msg->setMaxLineLength(40);
		$msg->setMsg($text);
		$this->assertSame(array(3, 5), $msg->getLengthyLines());
	}

	public function testIsValidTypeReturnsTrueForValidTypes() {
		$types = CommitMessage::getValidTypes();

		foreach ($types as $type) {
			$msg = new CommitMessage("{$type}(component): Summary");
			$this->assertTrue($msg->isValidType(), "Invalid type `$type`.");
		}
	}

	public function testRemovesComments() {
		$text = <<<___TEXT
These are lines of text
# this is a comment
# and another one.
And more text
___TEXT;
		$text = $this->convertLineEndings($text);

		$expected = "These are lines of text\nAnd more text";
		$this->assertSame($expected, CommitMessage::removeComments($text));
	}
	
	public function testReplaceLineEndings() {
		
		// Unix style (\n), this is what we want
		$text = "chore(tests): Some title\nWith a body";
		$msg = new CommitMessage($text);
		
		$this->assertSame($text, $msg->getMsg());
		
		// Commodore (\r)
		$text2 = "chore(tests): Some title\rWith a body";
		$msg = new CommitMessage($text2);
		
		$this->assertNotSame($text2, $msg->getMsg());
		
		// Windows (\r\n)
		$text3 = "chore(tests): Some title\r\nWith a body";
		$msg = new CommitMessage($text3);
		
		$this->assertNotSame($text3, $msg->getMsg());
	}

	/**
	 * Convert Windows (\r\n) and Commodore (\r) line endings to Unix (\n) line endings as the CommitMessage class does the same.
	 * This will prevent tests from failing on Windows machines
	 *
	 * @param string $text the commit message text
	 *
	 * @return string
	 */
	protected function convertLineEndings($text) {
		return str_replace(["\r\n", "\r"], "\n", $text);
	}
}
