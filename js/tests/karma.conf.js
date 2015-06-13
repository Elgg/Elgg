// Karma configuration
// Generated on Fri May 22 2015 12:59:26 GMT-0400 (EDT)

module.exports = function(config) {
	config.set({

		// base path that will be used to resolve all patterns (eg. files, exclude)
		basePath: '../..',


		// frameworks to use
		// available frameworks: https://npmjs.org/browse/keyword/karma-adapter
		frameworks: ['jasmine', 'requirejs'],


		// list of files / patterns to load in the browser
		files: [
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
		],


		// list of files to exclude
		exclude: [
		],


		// preprocess matching files before serving them to the browser
		// available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
		preprocessors: {
		},


		// test results reporter to use
		// possible values: 'dots', 'progress'
		// available reporters: https://npmjs.org/browse/keyword/karma-reporter
		reporters: ['progress'],


		// web server port
		port: 9876,


		// enable / disable colors in the output (reporters and logs)
		colors: true,


		// level of logging
		// possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
		logLevel: config.LOG_INFO,


		// enable / disable watching file and executing tests whenever any file changes
		autoWatch: false,


		// start these browsers
		// available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
		browsers: ['PhantomJS'],


		// Continuous Integration mode
		// if true, Karma captures browsers, runs the tests and exits
		singleRun: false
	});
};
