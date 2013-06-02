define(function(require) {
	
	var elgg = require('elgg');
	
	describe("elgg.session", function() {
		
		describe("#cookie()", function() {
			
			it("can get the cookie when called with no arguments", function() {
				expect(document.cookie).toEqual(elgg.session.cookie());	
			});
			
			it("can get the value of a particular key", function() {
				document.cookie = "name=value";
				expect('value').toEqual(elgg.session.cookie('name'));
				
				document.cookie = "name=value2";
				expect('value2').toEqual(elgg.session.cookie('name'));
				
				document.cookie = "name=value";
				document.cookie = "name2=value2";
				expect('value').toEqual(elgg.session.cookie('name'));
				expect('value2').toEqual(elgg.session.cookie('name2'));
				
			});
	
			it("can set the value of a particular key", function() {
				elgg.session.cookie('name', 'value');
				expect('value').toEqual(elgg.session.cookie('name'));
			
				elgg.session.cookie('name', 'value2');
				expect('value2').toEqual(elgg.session.cookie('name'));
				
				elgg.session.cookie('name', 'value');
				elgg.session.cookie('name2', 'value2');
				expect('value').toEqual(elgg.session.cookie('name'));
				expect('value2').toEqual(elgg.session.cookie('name2'));
				
				elgg.session.cookie('name', null);
				elgg.session.cookie('name2', null);
				expect(elgg.session.cookie('name')).toBe(undefined);
				expect(elgg.session.cookie('name2')).toBe(undefined);			
			});
	
		});
		
	});
});
