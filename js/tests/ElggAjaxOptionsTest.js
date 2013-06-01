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
		options = {success: func};
		result = elgg.ajax.handleOptions(options);
		
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
