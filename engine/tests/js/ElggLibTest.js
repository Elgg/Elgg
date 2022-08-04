define(function(require) {
	
	var elgg = require('elgg');
	
	describe("Elgg", function() {
		
		describe("elgg.parse_url()", function() {
			it("break urls down into component parts", function() {
				[
					["http://www.elgg.org/test/", {'scheme': 'http', 'host': 'www.elgg.org', 'path': '/test/'}],
					["https://www.elgg.org/test/", {'scheme': 'https', 'host': 'www.elgg.org', 'path': '/test/'}],
					["ftp://www.elgg.org/test/", {'scheme': 'ftp', 'host': 'www.elgg.org', 'path': '/test/'}],
					["http://elgg.org/test?val1=one&val2=two", {'scheme': 'http', 'host': 'elgg.org', 'path': '/test', 'query': 'val1=one&val2=two'}],
					["http://elgg.org:8080/", {'scheme': 'http', 'host': 'elgg.org', 'port': '8080', 'path': '/'}],
					["http://elgg.org/test#there", {'scheme': 'http', 'host': 'elgg.org', 'path': '/test', 'fragment': 'there'}],
					
					["test?val=one", {'host': 'test', 'query': 'val=one'}],
					["?val=one", {'query': 'val=one'}],
			
					["mailto:joe@elgg.org", {'scheme': 'mailto', 'path': 'joe@elgg.org'}],
					["javascript:load()", {'scheme': 'javascript', 'path': 'load()'}]
			
				].forEach(function(args) {
					expect(elgg.parse_url(args[0])).toEqual(args[1]);
				});		
			});		
		});
		
		describe("elgg.assertTypeOf()", function() {
			it("is a noop when the value is of the given type", function() {
				expect(function() {
				    elgg.assertTypeOf('string', '');
				    elgg.assertTypeOf('object', {});
				    elgg.assertTypeOf('boolean', true);
				    elgg.assertTypeOf('boolean', false);
				    elgg.assertTypeOf('undefined', undefined);
				    elgg.assertTypeOf('number', 1);
				    elgg.assertTypeOf('function', function() {});
				}).not.toThrow();
			});
			
			it("throws an exception when the value is not of the given type", function() {
				expect(function() { elgg.assertTypeOf('function', {}); }).toThrow();
				expect(function() { elgg.assertTypeOf('object', function() {}); }).toThrow();
			});
		});
		
		describe("elgg.normalize_url()", function() {
			var wwwroot;
			
			beforeEach(function() {
				wwwroot = elgg.config.wwwroot;
				elgg.config.wwwroot = 'http://elgg.org/';
			});
			
			afterEach(function() {
				elgg.config.wwwroot = wwwroot;
			})
			
			it("prepends elgg.config.wwroot to relative URLs", function() {
				[
					['', elgg.config.wwwroot],
					['test', elgg.config.wwwroot + 'test'],
					['mod/my_plugin/graphics/image.jpg', elgg.config.wwwroot + 'mod/my_plugin/graphics/image.jpg'],
			
					['page/handler', elgg.config.wwwroot + 'page/handler'],
					['page/handler?p=v&p2=v2', elgg.config.wwwroot + 'page/handler?p=v&p2=v2'],
					['mod/plugin/file.php', elgg.config.wwwroot + 'mod/plugin/file.php'],
					['mod/plugin/file.php?p=v&p2=v2', elgg.config.wwwroot + 'mod/plugin/file.php?p=v&p2=v2'],
					['rootfile.php', elgg.config.wwwroot + 'rootfile.php'],
					['rootfile.php?p=v&p2=v2', elgg.config.wwwroot + 'rootfile.php?p=v&p2=v2'],
	
					['/page/handler', elgg.config.wwwroot + 'page/handler'],
					['/page/handler?p=v&p2=v2', elgg.config.wwwroot + 'page/handler?p=v&p2=v2'],
					['/mod/plugin/file.php', elgg.config.wwwroot + 'mod/plugin/file.php'],
					['/mod/plugin/file.php?p=v&p2=v2', elgg.config.wwwroot + 'mod/plugin/file.php?p=v&p2=v2'],
					['/rootfile.php', elgg.config.wwwroot + 'rootfile.php'],
					['/rootfile.php?p=v&p2=v2', elgg.config.wwwroot + 'rootfile.php?p=v&p2=v2'],
					
					['livesearch?term=some.thing', elgg.config.wwwroot + 'livesearch?term=some.thing'],
					['livesearch#some.thing', elgg.config.wwwroot + 'livesearch#some.thing'],
					
					['/livesearch?term=some.thing', elgg.config.wwwroot + 'livesearch?term=some.thing'],
					['/livesearch#some.thing', elgg.config.wwwroot + 'livesearch#some.thing'],
				].forEach(function(args) {
					expect(elgg.normalize_url(args[0])).toBe(args[1]);
				});
			});
			
			it("leaves absolute and scheme-relative URLs alone", function() {
				[
					'http://example.com',
					'http://example.com/sub',
					'http://example-time.com',
					'http://example-time.com/sub',
					'https://example.com',
					'https://example.com/sub',

					'//example.com',

					'http://shouldn\'t matter what\'s here',
					'http://elgg_private.srokap.c9.io/',
					'https://shouldn\'t matter what\'s here',
					'ftp://example.com/file',
					'mailto:brett@elgg.org',
					'javascript:alert("test")',
					'app://endpoint',
					
					'http://example.com?foo=b.a.r',
					'http://example.com/sub?foo=b.a.r',
					'http://example.com?foo=b.a.r#some.id',
					'http://example.com/sub?foo=b.a.r#some.id',
					
				].forEach(function(url) {
					expect(elgg.normalize_url(url)).toBe(url);
				});
			});
			
			it("prepends scheme to domains that lack it", function() {
				[
					['example.com', 'http://example.com'],
					['example.com/subpage', 'http://example.com/subpage'],
					['example.com?foo=b.a.r', 'http://example.com?foo=b.a.r'],
					['example.com/subpage?foo=b.a.r', 'http://example.com/subpage?foo=b.a.r'],
					['example.com?foo=b.a.r#some.id', 'http://example.com?foo=b.a.r#some.id'],
					['example.com/subpage?foo=b.a.r#some.id', 'http://example.com/subpage?foo=b.a.r#some.id'],
				].forEach(function(args) {
					expect(elgg.normalize_url(args[0])).toBe(args[1]);
				});
			});
		});

		describe("elgg.parse_str()", function () {
			it("parses values like PHP's urldecode()", function () {
				[
					["A+%2B+B=A+%2B+B", {"A + B": "A + B"}],
					["key1=val1", {'key1': 'val1'}],
					["key1=val1&key2=val2", {'key1': 'val1', 'key2': 'val2'}],
					["key1[]=value1&key1[]=value2&key2=value3", {'key1': ['value1', 'value2'], 'key2': 'value3'}]
				].forEach(function(args) {
					expect(elgg.parse_str(args[0])).toEqual(args[1]);
				});
			});
		});
	});
});
