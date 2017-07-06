define(function (require) {

	var spinner = require('elgg/spinner');
	var elgg = require('elgg');
	var visible_selector = 'body.elgg-spinner-active';

	function onNextShow(f) {
		$(spinner).one('_testing_show', f);
	}

	describe("elgg/spinner", function () {
		beforeEach(function (done) {
			spinner.stop();
			setTimeout(done, 200);
		});

		it("indicator is in the DOM", function () {
			expect($('.elgg-spinner').length).toBe(1);
		});

		it("start() doesn't add the body class immediately", function () {
			expect($(visible_selector).length).toBe(0);
			spinner.start();
			expect($(visible_selector).length).toBe(0);
		});

		it("start() adds the body class after delay", function (done) {
			onNextShow(function () {
				expect($(visible_selector).length).toBe(1);
				done();
			});

			spinner.start();
		});

		it("start/stop can be called without 'this' set", function () {
			spinner.start.call(undefined);
			spinner.stop.call(undefined);
		});

		it("start(text) shows escaped text below the spinner", function (done) {
			expect($(visible_selector).length).toBe(0);

			onNextShow(function () {
				expect($(visible_selector).length).toBe(1);
				expect($('.elgg-spinner-text').html()).toBe('a&gt;b&amp;c');
				done();
			});

			spinner.start('a>b&c');
		});

		it("start([object]) sets empty text", function (done) {
			onNextShow(function () {
				expect($(visible_selector).length).toBe(1);
				expect($('.elgg-spinner-text').html()).toBe('');
				done();
			});

			spinner.start({});
		});

		it("start() removes any set text", function (done) {
			onNextShow(function () {
				onNextShow(function () {
					expect($('.elgg-spinner-text').html()).toBe('');
					done();
				});

				spinner.start();
			});

			spinner.start('a>b&c');
		});

		it("start([object]) removes any set text", function (done) {
			onNextShow(function () {
				onNextShow(function () {
					expect($('.elgg-spinner-text').html()).toBe('');
					done();
				});

				spinner.start({});
			});

			spinner.start('a>b&c');
		});

		it("stop() removes the body class", function (done) {
			onNextShow(function () {
				expect($(visible_selector).length).toBe(1);
				spinner.stop();
				expect($(visible_selector).length).toBe(0);
				done();
			});

			spinner.start();
		});
	});
});
