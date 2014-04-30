#!/usr/bin/env node

var pkg = require('../composer.json');
var fs = require('fs');
var changelog = require('elgg-conventional-changelog');

changelog({
  version: pkg.version,
  repository: 'https://github.com/Elgg/Elgg',
  types: {
      feature: 'Features',
      perf: 'Performance',
      docs: 'Documentation',
      fix: 'Bug Fixes',
      deprecate: 'Deprecations',
      breaks: 'Breaking Changes',
  }
}, function(err, log) {
  if (err) throw new Error(err);
  fs.writeFileSync('CHANGELOG.md', log);
});

