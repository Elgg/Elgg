define(function(require) {
	
	var i18n = require('elgg/i18n');
	
	describe("elgg.i18n", function() {
	
		afterEach(function() {
			i18n.reset();
		});
		
		describe("i18n.echo", function() {
	
			it("translates the given string", function() {
				i18n.addTranslation('en', {
					'hello': 'Hello!'
				});
				i18n.addTranslation('es', {
					'hello': 'Hola!'
				});
				
				expect(i18n.echo('hello')).toBe('Hello!');
				expect(i18n.echo('hello', 'es')).toBe('Hola!');			
			});
			
			it("falls back to the default language", function() {
				i18n.addTranslation('en', {
					'hello': 'Hello!'
				});
				
				expect(i18n.echo('hello', 'es')).toBe('Hello!');
			});

			it("recognizes empty string as a valid translation", function () {
				i18n.addTranslation('en', {
					'void': ''
				});

				expect(i18n.echo('void')).toBe('');
			});
		});
	});
});