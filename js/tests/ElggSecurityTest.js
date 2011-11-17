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

ElggSecurityTest.prototype.testAddTokenAcceptsRelativeUrl = function() {
	var input,
		str = "__elgg_ts=" + this.ts + "&__elgg_token=" + this.token;

	input = "test";
	assertEquals(input + '?' + str, elgg.security.addToken(input));
};

ElggSecurityTest.prototype.testAddTokenAcceptsFullUrl = function() {
	var input,
		str = "__elgg_ts=" + this.ts + "&__elgg_token=" + this.token;

	input = "http://elgg.org/";
	assertEquals(input + '?' + str, elgg.security.addToken(input));
};

ElggSecurityTest.prototype.testAddTokenAcceptsQueryString = function() {
	var input,
		str = "__elgg_ts=" + this.ts + "&__elgg_token=" + this.token;

	input = "?data=sofar";
	assertEquals(input + '&' + str, elgg.security.addToken(input));

	input = "test?data=sofar";
	assertEquals(input + '&' + str, elgg.security.addToken(input));

	input = "http://elgg.org/?data=sofar";
	assertEquals(input + '&' + str, elgg.security.addToken(input));
};

ElggSecurityTest.prototype.testAddTokenAlreadyAdded = function() {
	var input,
		str = "__elgg_ts=" + this.ts + "&__elgg_token=" + this.token;

	input = "http://elgg.org/?" + str + "&data=sofar";
	assertEquals(input, elgg.security.addToken(input));
};

ElggSecurityTest.prototype.testSetTokenSetsElggSecurityToken = function() {
	var json = {
		__elgg_ts: 4567,
		__elgg_token: 'abcdef'
	};
	
	elgg.security.setToken(json);
	assertEquals(json, elgg.security.token);
};
