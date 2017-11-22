#!/usr/bin/php
<?php
/**
 * Validates the text of a commit message.
 *
 * Text should be passed as the only argument, the path to a file (compatibility
 * with commit-msg git hook), or through stdin.
 *
 * Writes any errors to stdout.
 * Exits with 0 on success, > = 0 on failure.
 *
 * Can't pass multiple msgs at once.
 *
 * To use as a git commit hook, make sure the PHP path is correct, then
 * copy or symlink to .git/hooks/commit-msg.
 *
 */
 
$rootDir = dirname(__DIR__);
 
require_once "$rootDir/autoloader.php";

$is_file = false;

if ($argc === 2) {
	// check file or msg itself
	$arg = $argv[1];
	
	if (file_exists($arg)) {
		$is_file = true;
		$msg_tmp = file_get_contents($arg);
	} else {
		$msg_tmp = $arg;
	}
} else {
	// check for std in
	$msg_tmp = file_get_contents("php://stdin");
}

$msg = new Elgg\CommitMessage($msg_tmp);

if (!$msg->getMsg()) {
	usage();
}

if ($msg->shouldIgnore()) {
	output("Ignoring commit.", 'notice');
	exit(0);
}

// basic format
// don't continue if not correct
if (!$msg->isValidFormat()) {
	output("Fail.", 'error');
	output("Not in the format `type(component): summary`", 'error');
	output($msg, 'error');
	if ($is_file) {
		output("\nCommit message saved in " . $argv[1]);
	}
	exit(1);
}

$errors = array();

// line lengths
if (!$msg->isValidLineLength()) {
	$max = $msg->getMaxLineLength();
	foreach ($msg->getLengthyLines() as $line_num) {
		$errors[] = "Longer than $max characters at line $line_num";
	}
}

// type
if (!$msg->isValidType()) {
	$errors[] = "Invalid type at line 1: `{$msg->getPart('type')}`. Not one of "
		. implode(', ', $msg->getValidTypes()) . '.';
}

// component
// @todo only checking for existence right now via regex

// @todo check for fixes, refs, etc only in body and not in summary?
// @todo check for correct syntax for breaks and deprecates?

if ($errors) {
	output('Fail', 'error');
	foreach ($errors as $error) {
		output($error, 'error');
	}
	$arg = escapeshellarg($msg);
	
	$cmd = "printf '%s' $arg | nl -ba";
	$output = shell_exec($cmd);
	output($output, 'error', false);
	if ($is_file) {
		output("\nCommit message saved in " . $argv[1]);
	}
	exit(1);
} else {
	// only if we're not in a git commit
	if (!$is_file) {
		output('Ok', 'success');
	}
	exit(0);
}


/**
 * Output a msg followed by a \n
 *
 * @param string $msg   The message to output
 * @param bool   $error If true, the message is in red.
 */
function output($msg, $type = 'message', $trailing_return = true) {
	$colors = array(
		'red' => '0;31',
		'green' => '0;32',
		'yellow' => '0;33'
	);

	$types = array(
		'message' => '',
		'error' => 'red',
		'notice' => 'yellow',
		'success' => 'green'
	);

	$n = $trailing_return ? "\n" : '';

	switch ($type) {
		case 'error':
		case 'notice':
		case 'success':
			$color = $colors[$types[$type]];
			echo "\033[{$color}m$msg\033[0m{$n}";
			break;

		case 'message':
		default;
			echo "$msg{$n}";
			break;
	}
}

/**
 * Print usage and exit with error.
 */
function usage() {
	output("Pass a commit message text or a file containing the text of a commit message as the only argument.");
	exit(1);
}
