var tests = [];
for (var file in window.__karma__.files) {
    if (/Test\.js$/.test(file)) {
        tests.push(file);
    }
}

requirejs.config({
    // Karma serves files from '/base'
    baseUrl: '/base/views/default/',
    paths: {
        'vendor': '../../vendor',
        'node_modules': '../../node_modules',
        'jquery-mockjax': '../../node_modules/jquery-mockjax/dist/jquery.mockjax',
    },

    // ask Require.js to load these files (all our tests)
    deps: tests,

    // start test run, once Require.js is done
    callback: window.__karma__.start
});
