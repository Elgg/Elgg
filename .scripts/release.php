<?php
/**
 * Make the release commit this will:
 * - Write the changelog
 * - Update the version in composer.json
 *
 * Usage: php .scripts/release.php <semver>
 */

if (!isset($argv[1]) || $argv[1] == '--help') {
	echo 'Usage: php .scripts/release.php <semver>' . PHP_EOL;
	exit;
}

$version = $argv[1];

// Verify that $version is a valid semver string
// Performing check according to: https://getcomposer.org/doc/04-schema.md#version
$regexp = '/^[0-9]+\.[0-9]+\.[0-9]+(?:-(?:alpha|beta|rc)\.[0-9]+)?$/';

$matches = [];
if (!preg_match($regexp, $version, $matches)) {
	echo 'Bad version format. You must follow the format of X.Y.Z with an optional suffix of'
	    . ' -alpha.N, -beta.N, or -rc.N (where N is a number).' . PHP_EOL;
	exit(1);
}

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

$elgg_path = dirname(__DIR__);

$branch = "release-{$version}";

// Setup
run_commands([
	// Version checks are here so we fail early if any deps are missing
	'git --version',
	'yarn --version',
	'node --version',

	"cd {$elgg_path}",
	"git checkout -B {$branch}",
]);

// Update version in composer.json
$composer_path = "{$elgg_path}/composer.json";
$composer_config = json_decode(file_get_contents($composer_path));
$composer_config->version = $version;
$json = json_encode($composer_config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
file_put_contents($composer_path, $json);

// make the new release
run_commands(array(
	// update hash in composer.lock, because version was updated and now there is a mismatch between .json and .lock
	'composer update --lock',

	// Generate changelog
	'yarn install',
	'node .scripts/write-changelog.js',

	// commit everything to GitHub
	'git add .',
	"git commit -am \"chore(release): v{$version}\"",
));
