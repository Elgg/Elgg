/**
 * Test basic elgg library functions
 */
ElggLibTest = TestCase("ElggLibTest");

ElggLibTest.prototype.testGlobal = function() {
	assertTrue(window === elgg.global);
};

ElggLibTest.prototype.testAssertTypeOf = function() {
	var noexceptions = [
	    ['string', ''],
        ['object', {}],
        ['boolean', true],          
        ['boolean', false],         
        ['undefined', undefined],   
        ['number', 0],             
        ['function', function() {}],
    ];
	
	for (var i in noexceptions) {
		assertNoException(function() { 
			elgg.assertTypeOf.apply(elgg, noexceptions[i]); 
		});
	}
	
	var exceptions = [
        ['function', {}],
        ['object', function() {}],
    ];
	
	for (var i in exceptions) {
		assertException(function() {
			elgg.assertTypeOf.apply(elgg, exceptions[i]);
		});
	}
};

ElggLibTest.prototype.testProvide = function() {
	elgg.provide('foo.bar.baz');
	
	assertNotUndefined(foo);
	assertNotUndefined(foo.bar);
	assertNotUndefined(foo.bar.baz);
	
	var str = foo.bar.baz.oof = "don't overwrite me";
	
	elgg.provide('foo.bar.baz');
	
	assertEquals(str, foo.bar.baz.oof);
};

ElggLibTest.prototype.testRequire = function() {
	/* Try requiring bogus input */
	assertException(function(){ elgg.require(''); });
	assertException(function(){ elgg.require('garbage'); });
	assertException(function(){ elgg.require('gar.ba.ge'); });

	assertNoException(function(){ elgg.require('jQuery'); });
	assertNoException(function(){ elgg.require('elgg'); });
	assertNoException(function(){ elgg.require('elgg.config'); });
	assertNoException(function(){ elgg.require('elgg.security'); });
};

ElggLibTest.prototype.testInherit = function() {
	function Base() {}
	function Child() {}
	
	elgg.inherit(Child, Base);
	
	assertInstanceOf(Base, new Child());
	assertEquals(Child, Child.prototype.constructor);
};

ElggLibTest.prototype.testExtendUrl = function() {
	var url;
	elgg.config.wwwroot = "http://www.elgg.org/";
	
	url = '';
	assertEquals(elgg.config.wwwroot, elgg.extendUrl(url));
	
	url = 'pg/test';
	assertEquals('http://www.elgg.org/pg/test', elgg.extendUrl(url));
};