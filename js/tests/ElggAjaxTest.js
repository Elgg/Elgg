describe("elgg.ajax", function() {
	var wwwroot, ajax;
	
	beforeEach(function() {
		wwwroot = elgg.config.wwwroot;
		ajax = $.ajax;
		
		elgg.config.wwwroot = 'http://www.elgg.org/';
		
		$.ajax = function(options) {
			return options;
		};
	});
	
	afterEach(function() {
		$.ajax = ajax;
		elgg.config.wwwroot = wwwroot;		
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
