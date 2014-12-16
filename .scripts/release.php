<?php

if (!isset($argv[1]) || $argv[1] == '--help') {
    echo "Usage: php .scripts/release.php <semver>\n";
    exit;
}

$version = $argv[1];

// Verify that $version is a valid semver string
// Performing check according to: https://getcomposer.org/doc/04-schema.md#version
$regexp = '/^[0-9]+\.[0-9]+\.[0-9]+(?:-(?:dev|rc\.[0-9]+))?$/';

if (!preg_match($regexp, $version, $matches)) {
	echo "Bad version format. You must follow the format of X.Y.Z with an optional suffix of -dev,"
		. " or -rc.N (where N is a number).\n";
	exit(1);
}

function run_commands($commands) {
	foreach ($commands as $command) {
		echo "$command\n";
		passthru($command, $returnVal);
		if ($returnVal !== 0) {
			echo "Error executing command! Interrupting!\n";
			exit(2);
		}
	}
}

$elggPath = dirname(__DIR__);

$branch = "release-$version";


// Setup. Version checks are here so we fail early if any deps are missing
run_commands(array(
	"tx --version",
	"git --version",
	"npm --version",
	"node --version",
	"sphinx-build --version",

	"cd $elggPath",
	"git checkout -B $branch",
));


// Update translations
run_commands(array(
	"tx pull -a --minimum-perc=100",
	"sphinx-build -b gettext docs docs/locale/pot",
	"sphinx-intl --locale-dir=docs/locale/ build",
	"git add .",
	"git commit -am \"chore(i18n): update translations\"",
));

// Update version in composer.json
$composerPath = "$elggPath/composer.json";
$composerJson = json_decode(file_get_contents($composerPath));
$composerJson->version = $version;
file_put_contents($composerPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Generate changelog
run_commands(array(
	"npm install && npm update",
	"node .scripts/write-changelog.js",
	"git add .",
	"git commit -am \"chore(release): v$version\"",
));
