<?php

if (!isset($argv[1]) || $argv[1] == '--help') {
	echo "Usage: php .scripts/release.php <semver>\n";
	exit;
}

$version = $argv[1];

// Verify that $version is a valid semver string
// Performing check according to: https://getcomposer.org/doc/04-schema.md#version
$regexp = '/^[0-9]+\.[0-9]+\.[0-9]+(?:-(?:alpha|beta|rc)\.[0-9]+)?$/';

if (!preg_match($regexp, $version, $matches)) {
	echo "Bad version format. You must follow the format of X.Y.Z with an optional suffix of"
	    . " -alpha.N, -beta.N, or -rc.N (where N is a number).\n";
	exit(1);
}

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

$elgg_path = dirname(__DIR__);

$branch = "release-$version";

// Setup. Version checks are here so we fail early if any deps are missing
run_commands([
	"git --version",
	"yarn --version",
	"node --version",
	"sphinx-build --version",

	"cd $elgg_path",
	"git checkout -B $branch",
]);

// Update version in composer.json
$encoding = new \Elgg\Json\EmptyKeyEncoding();

$composer_path = "$elgg_path/composer.json";
$composer_config = $encoding->decode(file_get_contents($composer_path));
$composer_config->version = $version;
$json = $encoding->encode($composer_config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents($composer_path, $json);

// make the new release
run_commands(array(
	// update hash in composer.lock, because version was updated and now there is a mismatch between .json and .lock
	"composer update --lock",
	// Generate changelog
	"sphinx-build -b gettext docs docs/locale/pot",
	"sphinx-intl build --locale-dir=docs/locale/",
	"yarn install",
	"node .scripts/write-changelog.js",
	// commit everything to github
	"git add .",
	"git commit -am \"chore(release): v$version\"",
));
