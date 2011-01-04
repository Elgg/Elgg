/**
 * Makes sure that each of the helper ajax functions ends up calling $.ajax
 * with the right options.
 */
ElggAjaxTest = TestCase("ElggAjaxTest");

ElggAjaxTest.prototype.setUp = function() {
	
	this.wwwroot = elgg.config.wwwroot;
	this.ajax = $.ajax;
	
	elgg.config.wwwroot = 'http://www.elgg.org/';
	
	$.ajax = function(options) {
		return options;
	};
};

ElggAjaxTest.prototype.tearDown = function() {
	$.ajax = this.ajax;
	elgg.config.wwwroot = this.wwwroot;
};

ElggAjaxTest.prototype.testElggAjax = function() {
	assertEquals(elgg.config.wwwroot, elgg.ajax().url);
};

ElggAjaxTest.prototype.testElggGet = function() {
	assertEquals('get', elgg.get().type);
};

ElggAjaxTest.prototype.testElggGetJSON = function() {
	assertEquals('json', elgg.getJSON().dataType);
};

ElggAjaxTest.prototype.testElggPost = function() {
	assertEquals('post', elgg.post().type);
};

ElggAjaxTest.prototype.testElggAction = function() {
	assertException(function() { elgg.action(); });
	assertException(function() { elgg.action({}); });
	
	var result = elgg.action('action');
	assertEquals('post', result.type);
	assertEquals('json', result.dataType);
	assertEquals(elgg.config.wwwroot + 'action/action', result.url);
	assertEquals(elgg.security.token.__elgg_ts, result.data.__elgg_ts);
};

ElggAjaxTest.prototype.testElggAPI = function() {
	assertException(function() { elgg.api(); });
	assertException(function() { elgg.api({}); });
	
	var result = elgg.api('method');
	assertEquals('json', result.dataType);
	assertEquals('method', result.data.method);
	assertEquals(elgg.config.wwwroot + 'services/api/rest/json/', result.url);
};
