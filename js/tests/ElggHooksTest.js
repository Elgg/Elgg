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

			it("handles names/types with periods", function() {
				expect(elgg.trigger_hook("fee.fum", "bar.bang")).toBe(null);

				elgg.register_hook_handler("fee.fum", "bar.bang", function () { return 1; });

				expect(elgg.trigger_hook("fee.fum", "bar.bang")).toBe(1);

				elgg.register_hook_handler("fee.fum", "all", function () { return 2; });

				expect(elgg.trigger_hook("fee.fum", "pow")).toBe(2);
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

					elgg.register_hook_handler(hook, type, function () {
						done.push(hook_type);
					}, i);
				});

				elgg.trigger_hook('foo', 'bar');

				expect(done).toEqual(todo);
			});
		});

		describe("elgg.trigger_hook()", function() {
			it("only calls sync handlers", function () {
				elgg.register_hook_handler('foo', 'bar', function (h, t, p, v) {
					return v + 1;
				});
				elgg.register_async_hook_handler('foo', 'bar', function (h, t, p, v, deferred) {
					deferred.resolve(v + 10);
				});

				expect(elgg.trigger_hook('foo', 'bar', null, 0)).toBe(1);
			});
		});

		describe("elgg.trigger_sync_hook()", function() {
			it("calls all handlers", function (done) {
				elgg.register_async_hook_handler('foo', 'bar', function (h, t, p, v, deferred) {
					setTimeout(function () {
						deferred.resolve(v + 5);
					}, 1);
				});
				elgg.register_hook_handler('foo', 'bar', function (h, t, p, v) {
					return v + 1;
				});
				elgg.register_async_hook_handler('foo', 'bar', function (h, t, p, v, deferred) {
					setTimeout(function () {
						deferred.resolve(v + 10);
					}, 1);
				});

				elgg.trigger_async_hook('foo', 'bar', null, 0).done(function (val) {
					expect(val).toBe(16);
					done();
				});
			});

			it("passes error to promise", function (done) {
				elgg.register_async_hook_handler('foo', 'bar', function (h, t, p, v, deferred) {
					setTimeout(function () {
						deferred.reject('fail');
					}, 1);
				});

				elgg.trigger_async_hook('foo', 'bar', null, 0).fail(function (err) {
					expect(err).toBe('fail');
					done();
				});
			});

			it("auto-rejects after time exceeded", function (done) {
				var tmp = elgg.config.async_handler_timeout;

				elgg.config.async_handler_timeout = 100;

				elgg.register_async_hook_handler('foo', 'bar', function (h, t, p, v, deferred) {
					setTimeout(function () {
						deferred.resolve(1);
					}, 200); // too slow
				});

				elgg.trigger_async_hook('foo', 'bar', null, 0).fail(function (err) {
					expect(err.message).toContain('within time limit');

					elgg.config.async_handler_timeout = tmp;
					done();
				});
			});
		});
		
		describe("elgg.register_hook_handler()", function() {
			it("only accepts functions as handlers", function() {
				expect(function() { elgg.register_hook_handler('str', 'str', 'oops'); }).toThrow();
			});
		});

		describe("elgg.unregister_hook_handler()", function() {
			it("can remove handlers", function() {
				elgg.register_hook_handler('foo', 'bar', function (h, t, p, v) {
					return v + 1;
				});
				elgg.register_hook_handler('foo', 'bar', elgg.abstractMethod);
				elgg.register_hook_handler('foo', 'bar', function (h, t, p, v) {
					return v + 2;
				});
				elgg.register_hook_handler('foo', 'bar', elgg.abstractMethod);

				expect(elgg.unregister_hook_handler('foo', 'bar', elgg.abstractMethod)).toBe(true);

				expect(elgg.unregister_hook_handler('foo', 'bar', elgg.abstractMethod)).toBe(false);

				expect(elgg.trigger_hook('foo', 'bar', null, 5)).toBe(8);
			});
		});
	});

	// note elgg/init and a fake boot module are defined in prepare.js
	describe("elgg/ready", function() {
		it("requires init (boots plugins and fires init) and fires ready", function(done) {
			elgg._test_signals = [];

			require(['elgg/ready'], function () {
				expect(elgg._test_signals).toEqual([
					'boot/example define',

					// boot Plugin inits are called
					'boot/example init',

					// init, system fired
					'boot/example init,system',

					// ready, system fired
					'boot/example ready,system'
				]);

				delete(elgg._test_signals);

				done();
			});
		});
	});
});
