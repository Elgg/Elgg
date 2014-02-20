define(function(require) {
	
	var elgg = require('elgg');
	
	describe("elgg.hooks", function() {
	
		beforeEach(function() {
			elgg.config.hooks = {};
			elgg.provide('elgg.config.hooks.all.all');		
		});
	
		describe("elgg.trigger_hook()", function() {
			it("return value defaults to null", function() {
				expect(elgg.trigger_hook("fee", "fum")).toBe(null);
				
				elgg.register_hook_handler('fee', 'fum', elgg.nullFunction);
				expect(elgg.trigger_hook("fee", "fum")).toBe(null);

				expect(elgg.trigger_hook('x', 'y', {}, null)).toBe(null);
				expect(elgg.trigger_hook('x', 'z', {}, false)).toBe(false);
			});

			it("handlers returning null/undefined don't change returnvalue", function() {
				elgg.register_hook_handler('test', 'test', elgg.nullFunction);
				expect(elgg.trigger_hook('test', 'test', {}, 1984)).toBe(1984);

				elgg.register_hook_handler('test', 'test', function(hook, type, params, value) {
					return undefined;
				});
				expect(elgg.trigger_hook('test', 'test', {}, 42)).toBe(42);
			});

			it("triggers handlers registered with 'all'", function() {
				elgg.register_hook_handler('all', 'bar', elgg.abstractMethod);
				expect(function() { elgg.trigger_hook('foo', 'bar'); }).toThrow();
			
				elgg.register_hook_handler('foo', 'all', elgg.abstractMethod);
				expect(function() { elgg.trigger_hook('foo', 'baz'); }).toThrow();
			
				elgg.register_hook_handler('all', 'all', elgg.abstractMethod);
				expect(function() { elgg.trigger_hook('pinky', 'winky'); }).toThrow();
			});
		});
		
		describe("elgg.register_hook_handler()", function() {
			it("only accepts functions as handlers", function() {
				expect(function() { elgg.register_hook_handler('str', 'str', 'oops'); }).toThrow();
			});
		});
	});
});
