define(function(require) {
	
	var elgg = require('elgg');
	
	describe("elgg.i18n", function() {
	
		afterEach(function() {
			elgg.config.translations = {};
		});

		describe("elgg/echo", function() {

			it("can translate without args", function(done) {
				require([
					"elgg/echo!next",
					"elgg/echo!next!en",
					"elgg/echo!next!es"
				], function (next, next_en, next_es) {
					expect(next()).toBe("Next");
					expect(next.found).toBe(true);

					expect(next_en()).toBe("Next");
					expect(next_en.found).toBe(true);

					expect(next_es()).toBe("Siguiente");
					expect(next_es.found).toBe(true);
					done();
				});
			});

			it("can translate with args", function(done) {
				require([
					"elgg/echo!js:lightbox:current",
					"elgg/echo!js:lightbox:current!es" // fallback
				], function (current, current_es) {
					expect(current(['one', 'three'])).toBe("image one of three");
					expect(current.found).toBe(true);

					expect(current_es(['one', 'three'])).toBe("image one of three");
					expect(current_es.found).toBe(true);
					done();
				});
			});

			it("returns keys that can't be found", function(done) {
				require(["elgg/echo!nonexistent"], function (nonexistent) {
					expect(nonexistent()).toBe("nonexistent");
					expect(nonexistent.found).toBe(false);
					done();
				});
			});

			it("loads late language set", function(done) {
				require([
					"elgg/echo!ajax:error",
					"elgg/echo!ajax:error!es"
				], function (error, error_es) {
					expect(error()).toBe("Unexpected error");
					expect(error.found).toBe(true);

					expect(error_es()).toBe("Error inesperado");
					expect(error_es.found).toBe(true);
					done();
				});
			});
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

			it("recognizes empty string as a valid translation", function () {
				elgg.add_translation('en', {
					'void': ''
				});

				expect(elgg.echo('void')).toBe('');
			});

			it("helps devs to migrate", function () {
				var tmp_depr = elgg.deprecated_notice,
					tmp_admin = elgg.is_admin_logged_in,
					captured;

				elgg.is_admin_logged_in = function () {
					return true;
				};
				elgg.deprecated_notice = function (msg) {
					captured = msg;
				};

				elgg.echo('hello');
				expect(captured).toContain('require("elgg/echo!hello")()');

				elgg.echo('hello', [42]);
				expect(captured).toContain('require("elgg/echo!hello")([42])');

				elgg.echo('hello', 'es');
				expect(captured).toContain('require("elgg/echo!hello!es")()');

				elgg.deprecated_notice = tmp_depr;
				elgg.is_admin_logged_in = tmp_admin;
			});
		});
	});
});