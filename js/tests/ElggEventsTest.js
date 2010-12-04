ElggEventsTest = TestCase("ElggEventsTest");

ElggEventsTest.prototype.setUp = function() {
	elgg.config.events = {};
	elgg.provide('elgg.config.events.all.all');
};

ElggEventsTest.prototype.testEventHandlersMustBeFunctions = function () {
	assertException(function() { elgg.register_event_handler('str', 'str', 'oops'); });
};

ElggEventsTest.prototype.testReturnValueDefaultsToTrue = function () {
	assertTrue(elgg.trigger_event('fee', 'fum'));

	elgg.register_event_handler('fee', 'fum', elgg.nullFunction);
	assertTrue(elgg.trigger_event('fee', 'fum'));
};

ElggEventsTest.prototype.testCanGlomEventsWithAll = function () {
	elgg.register_event_handler('all', 'bar', elgg.abstractMethod);
	assertException("all,bar", function() { elgg.trigger_event('foo', 'bar'); });

	elgg.register_event_handler('foo', 'all', elgg.abstractMethod);
	assertException("foo,all", function() { elgg.trigger_event('foo', 'baz'); });

	elgg.register_event_handler('all', 'all', elgg.abstractMethod);
	assertException("all,all", function() { elgg.trigger_event('pinky', 'winky'); });
};