var tests = [];
for (var file in window.__karma__.files) {
    if (/Test\.js$/.test(file)) {
        tests.push(file);
    }
}

// This module is typically built in PHP. We can't do that with the test runner.
define('elgg', function() { return elgg; });

requirejs.config({
    // Karma serves files from '/base'
    baseUrl: '/base/views/default/js/',

    // ask Require.js to load these files (all our tests)
    deps: tests,

    // start test run, once Require.js is done
    callback: window.__karma__.start
});
