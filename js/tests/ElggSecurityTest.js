ElggSecurityTest = TestCase("ElggSecurityTest");

ElggSecurityTest.prototype.setUp = function() {
	//fill with fake, but reasonable, values for testing
	this.ts = elgg.security.token.__elgg_ts = 12345;
	this.token = elgg.security.token.__elgg_token = 'abcdef';
};

ElggSecurityTest.prototype.testAddTokenAcceptsUndefined = function() {
	var input,
		expected = {
			__elgg_ts: this.ts,
			__elgg_token: this.token
		};
	
	assertEquals(expected, elgg.security.addToken(input));
};

ElggSecurityTest.prototype.testAddTokenAcceptsObject = function() {
	var input = {},
		expected = {
			__elgg_ts: this.ts,
			__elgg_token: this.token
		};
	
	assertEquals(expected, elgg.security.addToken(input));
};

ElggSecurityTest.prototype.testAddTokenAcceptsString = function() {
	var input,
		str = "__elgg_ts=" + this.ts + "&__elgg_token=" + this.token;
	
	input = "";
	assertEquals(str, elgg.security.addToken(input));
	
	input = "data=sofar";
	assertEquals(input+'&'+str, elgg.security.addToken(input));
	
};

ElggSecurityTest.prototype.testSetTokenSetsElggSecurityToken = function() {
	var json = {
		__elgg_ts: 4567,
		__elgg_token: 'abcdef'
	};
	
	elgg.security.setToken(json);
	assertEquals(json, elgg.security.token);
};


