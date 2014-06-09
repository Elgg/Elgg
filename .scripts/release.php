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
		. " or -rc.N (where N is a number).";
	exit(1);
}

$elggPath = dirname(__DIR__);

// Update version in composer.json
$composerPath = "$elggPath/composer.json";
$composerJson = json_decode(file_get_contents($composerPath));
$composerJson->version = $version;
file_put_contents($composerPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


$branch = "release-$version";
$commands = array(
	"cd $elggPath",
	"git checkout -B $branch",
	"npm install && npm update",
	"node .scripts/write-changelog.js",
	"tx pull -a --minimum-perc=100",
	"git add .",
	"git commit -am \"chore(release): v$version\"",
	// "git push origin $branch",
);

foreach ($commands as $command) {
	echo "$command\n";
	passthru($command, $returnVal);
	if ($returnVal !== 0) {
		echo "Error executing command! Interrupting!";
		exit(2);
	}
}
