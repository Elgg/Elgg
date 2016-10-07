#!/usr/bin/env node

var pkg = require('../composer.json');
var fs = require('fs');
var changelog = require('elgg-conventional-changelog');

changelog({
	version: pkg.version,
	repository: 'https://github.com/Elgg/Elgg',
	types: {
		feat: 'Features',
		feature: 'Features',
		perf: 'Performance',
		performance: 'Performance',
		doc: 'Documentation',
		docs: 'Documentation',
		fix: 'Bug Fixes',
		fixes: 'Bug Fixes',
		fixed: 'Bug Fixes',
		deprecate: 'Deprecations',
		deprecates: 'Deprecations',
		break: 'Breaking Changes',
		breaks: 'Breaking Changes'
	}
}, function (err, log) {
	if (err)
		throw new Error(err);
	fs.writeFileSync('CHANGELOG.md', log);
});

