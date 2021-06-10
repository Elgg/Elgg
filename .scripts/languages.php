<?php

if (!isset($argv[1]) || $argv[1] == '--help') {
	echo "Usage: php .scripts/languages.php <branch>\n";
	exit;
}

$branch = $argv[1];

require_once dirname(__DIR__) . '/vendor/autoload.php';

function run_commands($commands) {
	foreach ($commands as $command) {
		echo "$command\n";
		passthru($command, $return_val);
		if ($return_val !== 0) {
			echo "Error executing command! Interrupting!\n";
			exit(2);
		}
	}
}

$new_branch = "{$branch}_i18n_" . time();

$elgg_path = dirname(__DIR__);

// Setup. Version checks are here so we fail early if any deps are missing
run_commands([
	"tx --version",
	"git --version",

	"cd $elgg_path",
	"git checkout -B $new_branch",
	"tx pull -af --minimum-perc=60 --mode translator",
]);

// Clean translations
\Elgg\Application::getInstance();

$cleaner = new Elgg\I18n\ReleaseCleaner();
$cleaner->cleanInstallation(dirname(__DIR__));
foreach ($cleaner->log as $msg) {
	echo "ReleaseCleaner: $msg\n";
}

run_commands([
	"git add .",
	"git commit -am \"chore(i18n): update translations\"",
]);

echo "Please submit '$new_branch' as a pull request:\n\n";
echo "   git push -u <fork_remote> $new_branch\n";
