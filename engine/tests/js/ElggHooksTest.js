define(function(require) {
	
	var hooks = require('elgg/hooks');
	
	describe("elgg/hooks", function() {
	
		beforeEach(function() {
			hooks.reset();		
		});
	
		describe("hooks.trigger()", function() {
			it("return value defaults to null", function() {
				expect(hooks.trigger("fee", "fum")).toBe(null);
				
				hooks.register('fee', 'fum', function() {});
				expect(hooks.trigger("fee", "fum")).toBe(null);

				expect(hooks.trigger('x', 'y', {}, null)).toBe(null);
				expect(hooks.trigger('x', 'z', {}, false)).toBe(false);
			});

			it("handlers returning null/undefined don't change returnvalue", function() {
				hooks.register('test', 'test', function() {});
				expect(hooks.trigger('test', 'test', {}, 1984)).toBe(1984);

				hooks.register('test', 'test', function(hook, type, params, value) {
					return undefined;
				});
				expect(hooks.trigger('test', 'test', {}, 42)).toBe(42);
			});

			it("triggers handlers registered with 'all'", function() {
				hooks.register('all', 'bar', function() {
					throw new Error("Oops... you forgot to implement an abstract method!");
				});
				expect(function() { hooks.trigger('foo', 'bar'); }).toThrow();
			
				hooks.register('foo', 'all', function() {
					throw new Error("Oops... you forgot to implement an abstract method!");
				});
				expect(function() { hooks.trigger('foo', 'baz'); }).toThrow();
			
				hooks.register('all', 'all', function() {
					throw new Error("Oops... you forgot to implement an abstract method!");
				});
				expect(function() { hooks.trigger('pinky', 'winky'); }).toThrow();
			});

			it("handles names/types with periods", function() {
				expect(hooks.trigger("fee.fum", "bar.bang")).toBe(null);

				hooks.register("fee.fum", "bar.bang", function () { return 1; });

				expect(hooks.trigger("fee.fum", "bar.bang")).toBe(1);

				hooks.register("fee.fum", "all", function () { return 2; });

				expect(hooks.trigger("fee.fum", "pow")).toBe(2);
			});

			it("calls handlers in priority order despite use of 'all'", function() {
				var todo = [
					'foo,bar',
					'foo,all',
					'all,bar',
					'foo,bar',
					'all,all',
					'foo,bar'
				];
				var done = [];

				$.each(todo, function (i, hook_type) {
					var hook = hook_type.split(',')[0];
					var type = hook_type.split(',')[1];

					hooks.register(hook, type, function () {
						done.push(hook_type);
					}, i);
				});

				hooks.trigger('foo', 'bar');

				expect(done).toEqual(todo);
			});
		});
		
		describe("hooks.register()", function() {
			it("only accepts functions as handlers", function() {
				expect(function() { hooks.register('str', 'str', 'oops'); }).toThrow();
			});
		});
	});
});
