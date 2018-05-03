define(function(require) {
	
	var elgg = require('elgg');
	var vsprintf = require('vendor/bower-asset/sprintf/src/sprintf');
	
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

			it("translates the given string with arguments", function() {
				elgg.add_translation('en', {
					'hello': 'Hello, %s!'
				});
				elgg.add_translation('es', {
					'hello': 'Hola, %s!'
				});

				expect(elgg.echo('hello', ['World'])).toBe('Hello, World!');
				expect(elgg.echo('hello', ['Mundo'], 'es')).toBe('Hola, Mundo!');
			});

			it("translates the given string with mustache arguments", function() {
				elgg.add_translation('en', {
					'hello': 'Hello, {{ name }}!'
				});
				elgg.add_translation('es', {
					'hello': 'Hola, {{ name }}!'
				});

				expect(elgg.echo('hello', {name: 'World'})).toBe('Hello, World!');
				expect(elgg.echo('hello', {name: 'Mundo'}, 'es')).toBe('Hola, Mundo!');
			});

			it("translates the given string with mustache arguments using dot notation", function() {
				elgg.add_translation('en', {
					'hello': 'Hello, {{ actor.name }}!'
				});
				elgg.add_translation('es', {
					'hello': 'Hola, {{ actor.name }}!'
				});

				expect(elgg.echo('hello', {actor: { name: 'World'}})).toBe('Hello, World!');
				expect(elgg.echo('hello', {actor: {name: 'Mundo'}}, 'es')).toBe('Hola, Mundo!');
			});

			it("translates the given string with mixed arguments", function() {
				elgg.add_translation('en', {
					'hello': 'Hello, {{ name }}! It\'s %s'
				});
				elgg.add_translation('es', {
					'hello': 'Hola, {{ name }}! Es el %s'
				});

				expect(elgg.echo('hello', {0: 2018, name: 'World'})).toBe('Hello, World! It\'s 2018');
				expect(elgg.echo('hello', {0: 2018, name: 'Mundo'}, 'es')).toBe('Hola, Mundo! Es el 2018');
			});

			it("falls back to the default language", function() {
				elgg.add_translation('en', {
					'hello': 'Hello!'
				});
				
				expect(elgg.echo('hello', 'es')).toBe('Hello!');
			});

			it("recognizes empty string as a valid translation", function () {
				elgg.add_translation('en', {
					'void': ''
				});

				expect(elgg.echo('void')).toBe('');
			});
		});
	});
});