<?php

if (php_sapi_name() !== "cli") {
	die('CLI only');
}

$root = dirname(__DIR__);
if (!is_writable($root)) {
	echo "$root is not writable.\n";
	exit(1);
}

require "$root/engine/classes/Elgg/Project/CodeStyle.php";

$style = new Elgg\Project\CodeStyle();

$report = $style->fixDirectory($root);
if (!$report) {
	exit;
}

$json_opts = defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0;
echo json_encode($report, $json_opts) . "\n";
