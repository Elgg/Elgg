define(function(require) {
	
	var elgg = require('elgg');
	
	describe("Elgg", function() {
	
		it("gives access to window via elgg.global", function() {
			expect(elgg.global).toBe(window);
		});
		
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
				    elgg.assertTypeOf('function', elgg.nullFunction);
				}).not.toThrow();
			});
			
			it("throws an exception when the value is not of the given type", function() {
				expect(function() { elgg.assertTypeOf('function', {}); }).toThrow();
				expect(function() { elgg.assertTypeOf('object', elgg.nullFunction); }).toThrow();
			});
		});
		
		describe("elgg.provide()", function() {
			it("generates a global namespace", function() {
				expect(window.foo).toBe(undefined);
				
				elgg.provide('foo.bar.baz');
				
				expect(window.foo.bar.baz).not.toBe(undefined);
				
				window.foo = undefined; // cleanup
			});
			
			it("plays nice with existing namespaces", function() {
				elgg.provide('foo.bar.baz');
			
				window.foo.bar.baz.oof = "test";
			
				elgg.provide('foo.bar.baz');
			
				expect(window.foo.bar.baz.oof).toBe("test");
				
				window.foo = undefined; // cleanup
			});

			it("can handle array names with periods", function () {
				elgg.provide(['foo', 'bar.baz']);

				expect(window.foo['bar.baz']).not.toBe(undefined);
			});
		});
		
		describe("elgg.require()", function() {
			it("is a noop if the namespace exists", function() {
				expect(function(){
					elgg.require('jQuery');
					elgg.require('elgg');
					elgg.require('elgg.config');
					elgg.require('elgg.security');
				}).not.toThrow();
			});
			
			it("throws an exception when then the namespace does not exist", function() {
				expect(function(){ elgg.require(''); }).toThrow();
				expect(function(){ elgg.require('garbage'); }).toThrow();
				expect(function(){ elgg.require('gar.ba.ge'); }).toThrow();			
			});
		});
		
		describe("elgg.inherit()", function() {
			function Base() {}
			
			function Child() {}	
			elgg.inherit(Child, Base);
	
			it("establishes an inheritance relationship between classes", function() {
				expect(new Child() instanceof Base).toBe(true);
			});
			
			it("preserves the constructor prototype property", function() {
				expect(Child.prototype.constructor).toBe(Child);
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
