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

if ($argc === 2) {
	// check file or msg itself
	$arg = $argv[1];
	
	if (file_exists($arg)) {
		$msg = file_get_contents($arg);
	} else {
		$msg = $arg;
	}
} else {
	// check for std in
	$msg = file_get_contents("php://stdin");
}

$msg = rtrim($msg);

if (!$msg) {
	usage();
}

$max_line_length = 100;

/**
 * Validate types
 */
$types = array(
	'feature',
	'fix',
	'docs',
	'chore',
	'perf',
	'security'
);

// these aren't tested for value yet, only for existence.
$components = array(
	'i18n',
	'seo',
	'a11y',
	'cache',
	'db',
	'views',
	'session',
	'router'
);

// regex to ignore commits
$ignore_test = '/^(Merge pull request)|(Merge [0-9a-f]{5,40} into [0-9a-f]{5,40})/i';

if (preg_match($ignore_test, $msg)) {
	output("Ignoring commit.", 'notice');
	exit(0);
}

/**
 * Checks for: type(component): message
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
 */
$test = "/^(\w*)\(([\w]+)\)\: ([^\n]*)(\n\n?(.*))?$/is";

// basic format
// can't continue if not at least close
if (!preg_match($test, $msg, $matches)) {
	output("Fail.", 'error');
	output("Not in the format `type(component): summary`", 'error');
	output($msg, 'error');
	exit(1);
}

$msg_info = array(
	'type' => $matches[1],
	'component' => $matches[2],
	'summary' => $matches[3]
);

if (isset($matches[5])) {
	$msg_info['body'] = $matches[5];
}

$errors = array();

// max line length
$lines = explode("\n", $msg);

array_walk($lines, function($line, $i) use ($max_line_length, $msg, &$errors) {
	if (strlen($line) > $max_line_length) {
		$line_num = ++$i;
		$errors[] = "Longer than $max_line_length characters at line $line_num";
	}
});

// type
if (!in_array($msg_info['type'], $types)) {
	$errors[] = "Invalid type at line 1: `{$msg_info['type']}`. Not one of "
		. implode(', ', $types) . '.';
}

// component
// @todo only checking for existence right now via regex
//if (!in_array($msg_info['component'], $components)) {
//	$errors[] = "Invalid component: `{$msg_info['component']}`";
//}

if ($errors) {
	output('Fail', 'error');
	foreach ($errors as $error) {
		output($error, 'error');
	}
	$arg = escapeshellarg($msg);
	
	$cmd = "printf '%s' $arg | nl -ba";
	$output = shell_exec($cmd);
	output($output, 'error', false);
	exit(1);
} else {
	output('Ok', 'success');
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
