ElggSessionTest = TestCase("ElggSessionTest");

ElggSessionTest.prototype.testGetCookie = function() {
	assertEquals(document.cookie, elgg.session.cookie());
};

ElggSessionTest.prototype.testGetCookieKey = function() {
	document.cookie = "name=value";
	assertEquals('value', elgg.session.cookie('name'));
	
	document.cookie = "name=value2";
	assertEquals('value2', elgg.session.cookie('name'));
	
	document.cookie = "name=value";
	document.cookie = "name2=value2";
	assertEquals('value', elgg.session.cookie('name'));
	assertEquals('value2', elgg.session.cookie('name2'));
};

ElggSessionTest.prototype.testSetCookieKey = function() {
	elgg.session.cookie('name', 'value');
	assertEquals('value', elgg.session.cookie('name'));

	elgg.session.cookie('name', 'value2');
	assertEquals('value2', elgg.session.cookie('name'));
	
	elgg.session.cookie('name', 'value');
	elgg.session.cookie('name2', 'value2');
	assertEquals('value', elgg.session.cookie('name'));
	assertEquals('value2', elgg.session.cookie('name2'));
	
	elgg.session.cookie('name', null);
	elgg.session.cookie('name2', null);
	assertUndefined(elgg.session.cookie('name'));
	assertUndefined(elgg.session.cookie('name2'));
};