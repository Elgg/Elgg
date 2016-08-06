var site = require('../config/site');

describe('home page', function() {
	it('should load with expected title', function () {
		browser.url(site.url);

		expect(browser.getTitle()).toBe(site.title);
	});
});
