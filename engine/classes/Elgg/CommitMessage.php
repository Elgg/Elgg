<?php
namespace Elgg;

use UnexpectedValueException;


/**
 * Provides a structured format for parsing and examining our commit messages.
 *
 * @package Elgg.Core
 * @since   1.9
 *
 * @access  private
 */
class CommitMessage {
	/**
	 * Valid parts of the message
	 * The index is the index of the $matches array for regex
	 * @var array
	 */
	private $validMsgParts = [
		1 => 'type',
		2 => 'component',
		3 => 'summary',
		5 => 'body'
	];

	/**
	 * Message type
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Message component
	 *
	 * @var string
	 */
	private $component;

	/**
	 * Message summary
	 *
	 * @var string
	 */
	private $summary;

	/**
	 * Optional message body
	 *
	 * @var string
	 */
	private $body;

	/**
	 * Original message text
	 *
	 * @var string
	 */
	private $originalMsg = '';

	/**
	 * Modified message text.
	 *
	 * @var string
	 */
	private $msg = '';

	/**
	 * An array of lines over the valid length
	 *
	 * @var array
	 */
	private $lengthyLines;

	/**
	 * Valid types
	 *
	 * @var array
	 */
	private static $validTypes = [
		'feature',
		'feat',
		'fix',
		'fixes',
		'fixed',
		'doc',
		'docs',
		'chore',
		'perf',
		'performance',
		'security',
		'deprecate',
		'deprecates'
	];

	/**
	 * Ignore messages that match this regex
	 *
	 * @var string
	 */
	private $ignoreRegex = '/^Merge |^Revert /i';

	/**
	 * Regex to extract the message parts
	 *
	 * type(component): message
	 * with an optional body following
	 *
	 * $matches = array(
	 *     0 => everything
	 *     1 => type
	 *     2 => component
	 *     3 => summary
	 *     4 => body (with leading \ns)
	 *     5 => body (without leading \ns)
	 * )
	 *
	 * @var string
	 */
	private $formatRegex = "/^(\w*)\(([\w]+)\)\: ([^\n]*)(\n\n?(.*))?$/is";

	/**
	 * Max length of any line
	 * @var int
	 */
	private $maxLineLength = 160;

	/**
	 * Checks if a commit message is in the correct format
	 *
	 * @param string|null $msg The commit message
	 */
	public function __construct($msg = null) {
		if ($msg) {
			$this->setMsg($msg);
		}
	}

	/**
	 * Sets the active message
	 *
	 * @param string $msg The message content
	 *
	 * @return void
	 */
	public function setMsg($msg) {
		$this->originalMsg = $msg;

		$msg = str_replace(["\r\n", "\r"], "\n", $msg);
		$this->msg = $this->removeComments($msg);
		$this->processMsg();
	}

	/**
	 * Return the processed message.
	 *
	 * @return string
	 */
	public function getMsg() {
		return $this->msg;
	}

	/**
	 * Return the original message
	 *
	 * @return string
	 */
	public function getOriginalMsg() {
		return $this->originalMsg;
	}

	/**
	 * Should this msg be ignored for formatting?
	 *
	 * @return boolean
	 */
	public function shouldIgnore() {
		return preg_match($this->ignoreRegex, $this->msg) === 1;
	}

	/**
	 * Process the msg into its parts
	 *
	 * @return array
	 */
	private function processMsg() {
		$matches = [];
		
		preg_match($this->formatRegex, $this->msg, $matches);
		foreach ($this->validMsgParts as $i => $part) {
			$this->$part = isset($matches[$i]) ? $matches[$i] : '';
		}

		$this->lengthyLines = $this->findLengthyLines($this->msg, $this->maxLineLength);
	}

	/**
	 * Are all parts of the message valid
	 *
	 * @return bool
	 */
	public function isValid() {
		return $this->isValidFormat() &&
				$this->isValidLineLength() &&
				$this->isValidType();
	}
	
	/**
	 * Whether the message format conforms to our standards.
	 *
	 * @return boolean
	 */
	public function isValidFormat() {
		return preg_match($this->formatRegex, $this->msg) === 1;
	}

	/**
	 * Are any of the lines too long?
	 *
	 * @see getLengthyLines() to get line numbers
	 *
	 * @return bool
	 */
	public function isValidLineLength() {
		return count($this->lengthyLines) === 0;
	}

	/**
	 * Get the line number of lines that are too long
	 *
	 * @return array
	 */
	public function getLengthyLines() {
		return $this->lengthyLines;
	}

	/**
	 * Is the type valid
	 *
	 * @return boolean
	 */
	public function isValidType() {
		return in_array($this->type, self::$validTypes);
	}

	/**
	 * Return all valid types
	 *
	 * @return array
	 */
	public static function getValidTypes() {
		return self::$validTypes;
	}

	/**
	 * Return the max line length
	 *
	 * @return int
	 */
	public function getMaxLineLength() {
		return $this->maxLineLength;
	}

	/**
	 * Sets the max line length allowed.
	 * Defaults to 160.
	 *
	 * @param int $len The maximum length.
	 *
	 * @return void
	 */
	public function setMaxLineLength($len) {
		$this->maxLineLength = (int) $len;
	}

	/**
	 * Get part of the message
	 *
	 * @param string $part One section of the message.
	 *
	 * @return string
	 * @throws UnexpectedValueException
	 */
	public function getPart($part) {
		if ($part && in_array($part, $this->validMsgParts)) {
			return $this->$part;
		}

		throw new UnexpectedValueException("`$part` not a valid message part.");
	}

	/**
	 * Removes all lines that start with #
	 *
	 * @param string $msg The msg body of the commit
	 *
	 * @return string
	 */
	public static function removeComments($msg) {
		$msg_arr = [];
		foreach (explode("\n", rtrim($msg)) as $line) {
			if (substr($line, 0, 1) !== '#') {
				$msg_arr[] = $line;
			}
		}

		return implode("\n", $msg_arr);
	}

	/**
	 * Returns an array of line numbers > $max_len
	 *
	 * @param string $msg     The content to parse
	 * @param int    $max_len Maximum length between \n in the $msg
	 *
	 * @return array
	 */
	public static function findLengthyLines($msg, $max_len) {
		$lines = explode("\n", $msg);
		$lengthy_lines = [];

		foreach ($lines as $i => $line) {
			if (strlen($line) > $max_len) {
				$lengthy_lines[] = ++$i;
			}
		}

		return $lengthy_lines;
	}

	/**
	 * {@inheritDoc}
	 */
	public function __toString() {
		return $this->getMsg();
	}
}
