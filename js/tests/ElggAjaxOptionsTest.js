/**
 * Tests elgg.ajax.handleOptions() with all of the possible valid inputs
 */
ElggAjaxOptionsTest = TestCase("ElggAjaxOptionsTest");

ElggAjaxOptionsTest.prototype.testHandleOptionsAcceptsNoArgs = function() {
	assertNotUndefined(elgg.ajax.handleOptions());
};

ElggAjaxOptionsTest.prototype.testHandleOptionsAcceptsUrl = function() {
	var url = 'url',
		result = elgg.ajax.handleOptions(url);
	
	assertEquals(url, result.url);
};

ElggAjaxOptionsTest.prototype.testHandleOptionsAcceptsDataOnly = function() {
	var options = {},
		result = elgg.ajax.handleOptions(options);
	
	assertEquals(options, result.data);
};

ElggAjaxOptionsTest.prototype.testHandleOptionsAcceptsOptions = function() {
	var options = {data:{arg:1}},
		result = elgg.ajax.handleOptions(options);
	
	assertEquals(options, result);
	
	function func() {}
	options = {success: func};
	result = elgg.ajax.handleOptions(options);
	
	assertEquals(options, result);
};

ElggAjaxOptionsTest.prototype.testHandleOptionsAcceptsUrlThenDataOnly = function() {
	var url = 'url',
		options = {arg:1},
		result = elgg.ajax.handleOptions(url, options);
	
	assertEquals(url, result.url);
	assertEquals(options, result.data);
};

ElggAjaxOptionsTest.prototype.testHandleOptionsAcceptsUrlThenSuccessOnly = function() {
	var url = 'url',
	result = elgg.ajax.handleOptions(url, elgg.nullFunction);
	
	assertEquals(url, result.url);
	assertEquals(elgg.nullFunction, result.success);
};

ElggAjaxOptionsTest.prototype.testHandleOptionsAcceptsUrlThenOptions = function() {
	var url = 'url',
	options = {data:{arg:1}},
	result = elgg.ajax.handleOptions(url, options);
	
	assertEquals(url, result.url);
	assertEquals(options.data, result.data);
};