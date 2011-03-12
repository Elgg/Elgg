ElggHooksTest = TestCase("ElggHooksTest");

ElggHooksTest.prototype.setUp = function() {
	elgg.config.hooks = {};
	elgg.provide('elgg.config.hooks.all.all');
};

ElggHooksTest.prototype.testHookHandlersMustBeFunctions = function () {
	assertException(function() { elgg.register_hook_handler('str', 'str', 'oops'); });
};

ElggHooksTest.prototype.testReturnValueDefaultsToTrue = function () {
	assertTrue(elgg.trigger_hook('fee', 'fum'));

	elgg.register_hook_handler('fee', 'fum', elgg.nullFunction);
	assertTrue(elgg.trigger_hook('fee', 'fum'));
};

ElggHooksTest.prototype.testCanGlomHooksWithAll = function () {
	elgg.register_hook_handler('all', 'bar', elgg.abstractMethod);
	assertException("all,bar", function() { elgg.trigger_hook('foo', 'bar'); });

	elgg.register_hook_handler('foo', 'all', elgg.abstractMethod);
	assertException("foo,all", function() { elgg.trigger_hook('foo', 'baz'); });

	elgg.register_hook_handler('all', 'all', elgg.abstractMethod);
	assertException("all,all", function() { elgg.trigger_hook('pinky', 'winky'); });
};