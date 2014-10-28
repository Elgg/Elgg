define(function(require) {

	var elgg = require('elgg');

	describe("elgg.i18n", function() {

		afterEach(function() {
			elgg.config.translations = {};
		});

		describe("elgg.echo", function() {

			it("translates the given string", function() {
				elgg.add_translation('en', {
					'hello': 'Hello!'
				});
				elgg.add_translation('es', {
					'hello': 'Hola!'
				});

				expect(elgg.echo('hello')).toBe('Hello!');
				expect(elgg.echo('hello', 'es')).toBe('Hola!');
			});

			it("falls back to the default language", function() {
				elgg.add_translation('en', {
					'hello': 'Hello!'
				});

				expect(elgg.echo('hello', 'es')).toBe('Hello!');
			});
		});
	});
});