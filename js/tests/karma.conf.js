// Karma configuration
// Generated on Sat Jun 01 2013 02:15:20 GMT-0400 (EDT)


// base path, that will be used to resolve files and exclude
basePath = '../..';


// list of files / patterns to load in the browser
files = [
  JASMINE,
  JASMINE_ADAPTER,
  REQUIRE,
  REQUIRE_ADAPTER,
  'vendors/jquery/jquery-1.11.0.min.js',
  'vendors/jquery/jquery-migrate-1.2.1.min.js',
  'vendors/sprintf.js',
  'js/lib/elgglib.js',
  'js/lib/hooks.js',
  'js/classes/*.js',
  'js/lib/*.js',

  {pattern:'js/tests/*Test.js',included: false},
  {pattern:'views/default/js/**/*.js',included:false},

  'js/tests/requirejs.config.js',
];


// list of files to exclude
exclude = [

];


// test results reporter to use
// possible values: 'dots', 'progress', 'junit'
reporters = ['progress'];


hostname = process.env.IP || 'localhost';


// web server port
port = process.env.PORT || 9876;


// cli runner port
runnerPort = 0;


// enable / disable colors in the output (reporters and logs)
colors = true;


// level of logging
// possible values: LOG_DISABLE || LOG_ERROR || LOG_WARN || LOG_INFO || LOG_DEBUG
logLevel = LOG_INFO;


// enable / disable watching file and executing tests whenever any file changes
autoWatch = true;


// Start these browsers, currently available:
// - Chrome
// - ChromeCanary
// - Firefox
// - Opera
// - Safari (only Mac)
// - PhantomJS
// - IE (only Windows)
browsers = ['PhantomJS'];


// If browser does not capture in given timeout [ms], kill it
captureTimeout = 60000;


// Continuous Integration mode
// if true, it capture browsers, run tests and exit
singleRun = false;
