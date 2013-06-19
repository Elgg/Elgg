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
		['http://example.com', 'http://example.com'],
		['https://example.com', 'https://example.com'],
		['http://example-time.com', 'http://example-time.com'],
		['//example.com', '//example.com'],
		['mod/my_plugin/graphics/image.jpg', elgg.config.wwwroot + 'mod/my_plugin/graphics/image.jpg'],

		['ftp://example.com/file', 'ftp://example.com/file'],
		['mailto:brett@elgg.org', 'mailto:brett@elgg.org'],
		['javascript:alert("test")', 'javascript:alert("test")'],
		['app://endpoint', 'app://endpoint'],

		['example.com', 'http://example.com'],
		['example.com/subpage', 'http://example.com/subpage'],

		['page/handler', elgg.config.wwwroot + 'page/handler'],
		['page/handler?p=v&p2=v2', elgg.config.wwwroot + 'page/handler?p=v&p2=v2'],
		['mod/plugin/file.php', elgg.config.wwwroot + 'mod/plugin/file.php'],
		['mod/plugin/file.php?p=v&p2=v2', elgg.config.wwwroot + 'mod/plugin/file.php?p=v&p2=v2'],
		['rootfile.php', elgg.config.wwwroot + 'rootfile.php'],
		['rootfile.php?p=v&p2=v2', elgg.config.wwwroot + 'rootfile.php?p=v&p2=v2'],

		['/page/handler', elgg.config.wwwroot + 'page/handler'],
		['/page/handler?p=v&p2=v2', elgg.config.wwwroot + 'page/handler?p=v&p2=v2'],
		['/mod/plugin/file.php', elgg.config.wwwroot + 'mod/plugin/file.php'],
		['/mod/plugin/file.php?p=v&p2=v2', elgg.config.wwwroot + 'mod/plugin/file.php?p=v&p2=v2'],
		['/rootfile.php', elgg.config.wwwroot + 'rootfile.php'],
		['/rootfile.php?p=v&p2=v2', elgg.config.wwwroot + 'rootfile.php?p=v&p2=v2']

	].forEach(function(args) {
		assertEquals(args[1], elgg.normalize_url(args[0]));
	});
};

ElggLibTest.prototype.testParseUrl = function() {

	[
		["http://www.elgg.org/test/", {'scheme': 'http', 'host': 'www.elgg.org', 'path': '/test/'}],
		["https://www.elgg.org/test/", {'scheme': 'https', 'host': 'www.elgg.org', 'path': '/test/'}],
		["ftp://www.elgg.org/test/", {'scheme': 'ftp', 'host': 'www.elgg.org', 'path': '/test/'}],
		["http://elgg.org/test?val1=one&val2=two", {'scheme': 'http', 'host': 'elgg.org', 'path': '/test', 'query': 'val1=one&val2=two'}],
		["http://elgg.org:8080/", {'scheme': 'http', 'host': 'elgg.org', 'port': 8080, 'path': '/'}],
		["http://elgg.org/test#there", {'scheme': 'http', 'host': 'elgg.org', 'path': '/test', 'fragment': 'there'}],
		
		["test?val=one", {'host': 'test', 'query': 'val=one'}],
		["?val=one", {'query': 'val=one'}],

		["mailto:joe@elgg.org", {'scheme': 'mailto', 'path': 'joe@elgg.org'}],
		["javascript:load()", {'scheme': 'javascript', 'path': 'load()'}]

	].forEach(function(args) {
		assertEquals(args[1], elgg.parse_url(args[0]));
	});
};

ElggLibTest.prototype.testParseStr = function() {

	[
		["A+%2B+B=A+%2B+B", {"A + B": "A + B"}]

	].forEach(function(args) {
		assertEquals(args[1], elgg.parse_str(args[0]));
	});
};

