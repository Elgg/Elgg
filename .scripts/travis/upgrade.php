<?php
/**
 * Runs upgrade.php
 * Runs async upgrades
 */

$root = dirname(dirname(__DIR__));
require_once "$root/autoloader.php";

\Elgg\Application::upgrade();

\Elgg\Application::start();

// set custom exception handler for cli
set_exception_handler(function (Throwable $e) {
	
	fwrite(STDERR, "An exception was thrown: {$e->getMessage()}" . PHP_EOL);
	fwrite(STDERR, "Filename: {$e->getFile()} on line {$e->getLine()}" . PHP_EOL);
	fwrite(STDERR, "{$e->getTraceAsString()}" . PHP_EOL);
	
	exit($e->getCode() ?: 1);
});

_elgg_generate_plugin_entities();

$core_upgrades = (require \Elgg\Application::elggDir()->getPath('engine/lib/upgrades/async-upgrades.php'));
$pending_upgrades = _elgg_services()->upgradeLocator->run($core_upgrades);

if (empty($pending_upgrades)) {
	fwrite(STDOUT, 'There no pending asynchronous upgrades' . PHP_EOL);
	exit();
}

$errors = false;
foreach ($pending_upgrades as $guid) {
	$upgrade = get_entity($guid);
	if (!$upgrade instanceof ElggUpgrade) {
		continue;
	}

	$upgrade_name = elgg_echo($upgrade->getDisplayName());

	fwrite(STDOUT, "Starting async upgrade {$upgrade_name}" . PHP_EOL);

	while (!$upgrade->isCompleted()) {
		$result = _elgg_services()->batchUpgrader->run($upgrade);

		if ($result['errors']) {
			foreach ($result['errors'] as $error) {
				fwrite(STDERR, "ERROR: $error" . PHP_EOL);
			}
		}

		if ($result['numErrors']) {
			fwrite(STDOUT, "Async upgrade {$upgrade_name} encountered {$result['numErrors']} errors" . PHP_EOL);
			$errors = true;
			break;
		}
	}

	if ($upgrade->isCompleted()) {
		fwrite(STDOUT, "Async upgrade {$upgrade_name} completed at {$upgrade->getCompletedTime()}" . PHP_EOL);
	}
}

if ($errors) {
	throw new InstallationException("One or more async upgrades have failed");
}
