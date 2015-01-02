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

		it("start() adds the body class", function() {
			expect($(visible_selector).length).toBe(0);
			spinner.start();
			expect($(visible_selector).length).toBe(1);
		});

		it("stop() removes the body class", function() {
			spinner.start();
			expect($(visible_selector).length).toBe(1);
			spinner.stop();
			expect($(visible_selector).length).toBe(0);
		});
	});
});
