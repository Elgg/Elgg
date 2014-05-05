<?php

if (!isset($argv[1]) || $argv[1] == '--help') {
    echo "Usage: php .scripts/release.php <semver>\n";
    exit;
}

$version = $argv[1];

// TODO: Verify that $version is a valid semver string

$elggPath = dirname(__DIR__);

// Update version in composer.json
$composerPath = "$elggPath/composer.json";
$composerJson = json_decode(file_get_contents($composerPath));
$composerJson->version = $version;
file_put_contents($composerPath, json_encode($composerJson));


$branch = "release-$version";
$commands = array(
    "pushd $elggPath",
    // must be separate from git checkout in case branch already exists
    "git branch $branch",
    "git checkout $branch",
    ".scripts/write-changelog.js",
    "tx pull -a --pseudo --minimum-perc=100",
    "git add .",
    "git commit -am 'chore(release): v$version'",
    // "git push origin $branch",
    "popd"
);

foreach ($commands as $command) {
    echo "$command\n";
    exec($command);
}
