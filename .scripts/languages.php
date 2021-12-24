<?php
/**
 * Pull the translations from Transifex and build the international docs
 *
 * Usage: php .scripts/languages.php <branch>
 */

if (!isset($argv[1]) || $argv[1] == '--help') {
	echo 'Usage: php .scripts/languages.php <branch>' . PHP_EOL;
	exit;
}

$branch = $argv[1];

require_once dirname(__DIR__) . '/vendor/autoload.php';

function run_commands($commands) {
	foreach ($commands as $command) {
		echo $command . PHP_EOL;
		
		$return_val = null;
		passthru($command, $return_val);
		if ($return_val !== 0) {
			echo 'Error executing command! Interrupting!' . PHP_EOL;
			exit(2);
		}
	}
}

$new_branch = "{$branch}_i18n_" . time();

$elgg_path = dirname(__DIR__);

// Setup
run_commands([
	// Version checks are here so we fail early if any deps are missing
	'tx --version',
	'git --version',
	'sphinx-build --version',

	"cd {$elgg_path}",
	"git checkout -B {$new_branch}",

	// pull translations
	'tx pull -af --minimum-perc=60 --mode translator',
]);

// Clean translations
\Elgg\Application::getInstance();

$cleaner = new Elgg\I18n\ReleaseCleaner();
$cleaner->cleanInstallation(dirname(__DIR__));
foreach ($cleaner->log as $msg) {
	echo "ReleaseCleaner: {$msg}" . PHP_EOL;
}

run_commands([
	// build international docs
	'sphinx-build -b gettext docs docs/locale/pot',
	'sphinx-intl build --locale-dir=docs/locale/',
	
	// commit everything to GitHub
	'git add .',
	"git commit -am \"chore(i18n): update translations\"",
]);

echo "Please submit '{$new_branch}' as a pull request:" . PHP_EOL . PHP_EOL;
echo "   git push -u <fork_remote> {$new_branch}" . PHP_EOL;
