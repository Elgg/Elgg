define(function(require) {
	
	var elgg = require('elgg');
	var security = require('elgg/security');
	
	describe('elgg/security', function() {
		var ts, token;
		
		beforeEach(function() {
			ts = elgg.security.token.__elgg_ts = 12345;
			token = elgg.security.token.__elgg_token = 'abcdef';
		});
	
		describe("addToken", function() {
			it("accepts undefined", function() {
				var expected = {
					__elgg_ts: ts,
					__elgg_token: token
				};
		
				expect(security.addToken(undefined)).toEqual(expected);
			});
			
			it("accepts an object", function() {
				var expected = {
					__elgg_ts: ts,
					__elgg_token: token
				};
		
				expect(security.addToken({})).toEqual(expected);
			});
			
			
			it("accepts relative urls", function() {
				var str = "__elgg_ts=" + ts + "&__elgg_token=" + token;
			
				expect(security.addToken("/test"), '/test?' + str);
			});
			
			it("accepts full urls", function() {
				var str = "__elgg_ts=" + ts + "&__elgg_token=" + token;
			
				var url = "http://elgg.org/";
				expect(security.addToken(url)).toEqual(url + '?' + str);
			});
			
			it("accepts query strings", function() {
				var str = "__elgg_ts=" + ts + "&__elgg_token=" + token;
				var url;
				
				url = "?data=sofar";
				expect(security.addToken(url), url + '&' + str);
			
				url = "test?data=sofar";
				expect(security.addToken(url), url + '&' + str);
			
				url = "http://elgg.org/?data=sofar";
				expect(security.addToken(url), url + '&' + str);
			});
			
			it("overwrites existing query string tokens", function() {
				var expectedUrl = "http://elgg.org/?__elgg_ts=" + ts + "&__elgg_token=" + token + "&data=sofar";
				var inputUrl = "http://elgg.org/?__elgg_ts=54321&__elgg_token=fedcba&data=sofar"
				
				expect(security.addToken(inputUrl)).toBe(expectedUrl);
			});
		});
	});
});
