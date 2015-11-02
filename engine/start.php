<?php

$autoload_root = dirname(__DIR__);
if (!is_file("$autoload_root/vendor/autoload.php")) {
	$autoload_root = dirname(dirname(dirname($autoload_root)));
}
require_once "$autoload_root/vendor/autoload.php";

\Elgg\Application::start();

elgg_deprecated_notice('You should load the core using \Elgg\Application::start() instead of including start.php', "2.0.0");
