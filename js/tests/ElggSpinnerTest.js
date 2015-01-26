define(function(require) {

	var Spinner = require('elgg/Spinner');
	var elgg = require('elgg');

	/**
	 * Jasmine helper: Call func sequentially after blocking for {delay} milliseconds
	 *
	 * @param {Number} delay
	 * @param {Function} func
	 *
	 * @link http://jasmine.github.io/1.3/introduction.html#section-Asynchronous_Support
	 */
	function runsAfter(delay, func) {
		var blocking = true;
		runs(function () {
			setTimeout(function () {
				blocking = false;
			}, delay);
		});
		waitsFor(delay * 2, function () {
			return !blocking;
		});
		runs(func);
	}

	describe("elgg/Spinner", function() {
		beforeEach(function () {
			Spinner._reset();
		});

		/**
		 * Note: Adding the spinner is synchronous but if we change the spinner to fade in, we
		 * will need to make the start() checks async as well.
		 */
		it("start() creates the indicator", function() {
			var spinner = new Spinner();

			expect($('.elgg-spinner:visible').length).toBe(0);
			spinner.start();

			expect($('.elgg-spinner:visible').length).toBe(1);
		});

		it("stop() hides the indicator", function() {
			var spinner = new Spinner({
				fadeOpts: {
					duration: 0
				}
			});

			spinner.start();
			spinner.stop();

			waitsFor(50, function () {
				return $('.elgg-spinner:visible').length == 0;
			});
		});

		it("Indicator stays until all instances stopped", function() {
			var opts = {
				fadeOpts: {
					duration: 0
				}
			};
			var s1 = new Spinner(opts);
			var s2 = new Spinner(opts);
			var may_continue = false;

			s1.start();
			s2.start();

			s1.stop();

			runsAfter(50, function () {
				// should still be visible
				expect($('.elgg-spinner:visible').length).toBe(1);

				s2.stop();
			});
			runsAfter(50, function () {
				// should still be gone
				expect($('.elgg-spinner:visible').length).toBe(0);

				s2.stop();
			});
		});

		it("Constructor enforces option types", function() {
			new Spinner({
				$wait: $()
			});
			expect(function () {
				new Spinner({
					$wait: 1
				});
			}).toThrow();

			new Spinner({
				fadeOpts: {}
			});
			expect(function () {
				new Spinner({
					fadeOpts: 1
				});
			}).toThrow();
		});

		it("Methods don't require instance this context", function () {
			var spinner = new Spinner({
				fadeOpts: {
					duration: 0
				}
			});

			spinner.start.apply(window);

			expect($('.elgg-spinner:visible').length).toBe(1);

			spinner.stop.apply(window);

			waitsFor("fadeOut to finish", function () {
				return $('.elgg-spinner:visible').length == 0;
			}, 50);
		});
	});
});
