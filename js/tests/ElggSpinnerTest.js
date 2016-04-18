define(function(require) {

	var spinner = require('elgg/spinner');
	var elgg = require('elgg');
	var visible_selector = 'body.elgg-spinner-active';

	describe("elgg/spinner", function() {
		beforeEach(function () {
			spinner.stop();
		});

		it("indicator is in the DOM", function () {
			expect($('.elgg-spinner').length).toBe(1);
		});

		it("start() doesn't add the body class immediately", function() {
			expect($(visible_selector).length).toBe(0);
			spinner.start();
			expect($(visible_selector).length).toBe(0);
		});

		it("start() adds the body class after 20ms", function(done) {
			expect($(visible_selector).length).toBe(0);
			spinner.start();

			setTimeout(function() {
				expect($(visible_selector).length).toBe(1);
				done();
			}, 25);
		});

		it("start/stop can be called without 'this' set", function() {
			spinner.start.call(undefined);
			spinner.stop.call(undefined);
		});

		it("start(text) shows escaped text below the spinner", function(done) {
			expect($(visible_selector).length).toBe(0);
			spinner.start('a>b&c');

			setTimeout(function() {
				expect($(visible_selector).length).toBe(1);
				expect($('.elgg-spinner-text').html()).toBe('a&gt;b&amp;c');
				done();
			}, 25);
		});

		it("start() removes any set text", function(done) {
			spinner.start('a>b&c');

			setTimeout(spinner.start, 25);

			setTimeout(function() {
				expect($('.elgg-spinner-text').html()).toBe('');
				done();
			}, 35);
		});

		it("stop() removes the body class", function(done) {
			spinner.start();

			setTimeout(function() {
				expect($(visible_selector).length).toBe(1);
				spinner.stop();
				expect($(visible_selector).length).toBe(0);
				done();
			}, 25);
		});
	});
});
