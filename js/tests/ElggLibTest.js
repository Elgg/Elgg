/**
 * Test basic elgg library functions
 */
ElggLibTest = TestCase("ElggLibTest");

ElggLibTest.prototype.testGlobal = function() {
	assertTrue(window === elgg.global);
};

ElggLibTest.prototype.testAssertTypeOf = function() {
	[//Valid inputs
	    ['string', ''],
        ['object', {}],
        ['boolean', true],
        ['boolean', false],
        ['undefined', undefined],
        ['number', 0],
        ['function', elgg.nullFunction]
    ].forEach(function(args) {
		assertNoException(function() {
			elgg.assertTypeOf.apply(undefined, args);
		});
	});

	[//Invalid inputs
        ['function', {}],
        ['object', elgg.nullFunction]
    ].forEach(function() {
		assertException(function(args) {
			elgg.assertTypeOf.apply(undefined, args);
		});
	});
};

ElggLibTest.prototype.testProvideDoesntClobber = function() {
	elgg.provide('foo.bar.baz');

	foo.bar.baz.oof = "test";

	elgg.provide('foo.bar.baz');

	assertEquals("test", foo.bar.baz.oof);
};

/**
 * Try requiring bogus input
 */
ElggLibTest.prototype.testRequire = function () {
	assertException(function(){ elgg.require(''); });
	assertException(function(){ elgg.require('garbage'); });
	assertException(function(){ elgg.require('gar.ba.ge'); });

	assertNoException(function(){
		elgg.require('jQuery');
		elgg.require('elgg');
		elgg.require('elgg.config');
		elgg.require('elgg.security');
	});
};

ElggLibTest.prototype.testInherit = function () {
	function Base() {}
	function Child() {}

	elgg.inherit(Child, Base);

	assertInstanceOf(Base, new Child());
	assertEquals(Child, Child.prototype.constructor);
};

ElggLibTest.prototype.testNormalizeUrl = function() {
	elgg.config.wwwroot = "http://elgg.org/";

	[
	    ['', elgg.config.wwwroot],
	    ['test', elgg.config.wwwroot + 'test'],
	    ['http://google.com', 'http://google.com'],
	    ['//example.com', '//example.com'],
	    ['/page', elgg.config.wwwroot + 'page'],
	    ['mod/plugin/index.php', elgg.config.wwwroot + 'mod/plugin/index.php'],
	].forEach(function(args) {
		assertEquals(args[1], elgg.normalize_url(args[0]));
	});
};