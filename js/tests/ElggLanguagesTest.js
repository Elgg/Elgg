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

			it("uses the first available translation key", function () {
				elgg.add_translation('en', {
					'fallback': 'Fallback',
				});

				expect(elgg.echo(['first_choice', 'fallback'])).toBe('Fallback');

				elgg.add_translation('en', {
					'first_choice': 'First Choice'
				});

				expect(elgg.echo(['first_choice', 'fallback'])).toBe('First Choice');
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