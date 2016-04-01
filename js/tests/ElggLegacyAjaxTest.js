define(function(require) {
	
	var elgg = require('elgg');
	
	describe("elgg.ajax", function() {
		var ajax;
		
		beforeEach(function() {
			ajax = $.ajax;
			
			$.ajax = function(options) {
				return options;
			};
		});
		
		afterEach(function() {
			$.ajax = ajax;
		});
		
		it("requests elgg.config.wwwroot by default", function() {
			expect(elgg.ajax().url).toBe(elgg.config.wwwroot);
		});
		
		it("can issue a GET using elgg.get()", function() {
			expect(elgg.get().type).toBe('get');	
		});
		
		it("can issue a POST using elgg.post()", function() {
			expect(elgg.post().type).toBe('post');	
		});
	
		it("can request JSON with elgg.getJSON()", function() {
			expect(elgg.getJSON().dataType).toBe('json');
		});
	
		describe("handleOptions", function() {
		
			it("accepts handleOptions()", function() {
				expect(elgg.ajax.handleOptions()).not.toBe(undefined);
			});
			
			it("accepts handleOptions(url)", function() {
				var url = 'http://google.com',
					result = elgg.ajax.handleOptions(url);
				
				expect(result.url).toBe(url);
			});
			
			it("interprets a POJO as data", function() {
				var options = {},
					result = elgg.ajax.handleOptions(options);
				
				expect(result.data).toBe(options);	
			});
			
			it("interprets a POJO with a data field as full options", function() {
				var options = {data:{arg:1}},
					result = elgg.ajax.handleOptions(options);
				
				expect(result).toBe(options);
			});
			
			it("interprets a POJO with a function as full options", function() {
				function func() {}
				var options = {success: func};
				var result = elgg.ajax.handleOptions(options);
				
				expect(result).toBe(options);		
			});
			
			it("accepts handleOptions(url, data)", function() {
				var url = 'url',
					data = {arg:1},
					result = elgg.ajax.handleOptions(url, data);
				
				expect(result.url).toBe(url);
				expect(result.data).toBe(data);
			});
			
			it("accepts handleOptions(url, successCallback)", function() {
				var url = 'http://google.com',
				result = elgg.ajax.handleOptions(url, elgg.nullFunction);
				
				expect(result.url).toBe(url);
				expect(result.success).toBe(elgg.nullFunction);
			});
			
			it("accepts handleOptions(url, options)", function() {
				var url = 'url',
				    options = {data:{arg:1}},
				    result = elgg.ajax.handleOptions(url, options);
				
				expect(result.url).toBe(url);
				expect(result.data).toBe(options.data);
			});
		});

		describe("elgg.action()", function() {
			it("issues a POST request", function() {
				var result = elgg.action('action');
				expect(result.type).toBe('post');
			});
			
			it("expects a JSON response", function() {
				var result = elgg.action('action');
				expect(result.dataType).toBe('json');			
			});
	
			it("accepts action names", function() {
				var result = elgg.action('action');
				expect(result.url).toBe(elgg.config.wwwroot + 'action/action');
			});
			
			it("accepts action URLs", function() {
				var result = elgg.action(elgg.config.wwwroot + 'action/action');
				expect(result.url).toBe(elgg.config.wwwroot + 'action/action');
			});
			
			it("includes CSRF tokens automatically in the request", function() {
				var result = elgg.action('action');
				expect(result.data.__elgg_ts).toBe(elgg.security.token.__elgg_ts);
			});
	
			it("throws an exception if you don't specify an action", function() {
				expect(function() { elgg.action(); }).toThrow();
				expect(function() { elgg.action({}); }).toThrow();
			});
		});
	});
});