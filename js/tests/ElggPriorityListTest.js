define(function(require) {
	
	var elgg = require('elgg');
	
	describe("elgg.ElggPriorityList", function() {
		var list;
		
		beforeEach(function() {
			list = new elgg.ElggPriorityList();	
		});
	
		describe("insert()", function() {
			it("defaults priority to 500", function() {
				list.insert('foo');
			
				expect(list.priorities_[500][0], 'foo');
			});
		});
		
		describe("forEach()", function() {
			it("returns elements in priority order", function() {
				var values = [5, 4, 3, 2, 1, 0];
			
				for (var i in values) {
					list.insert(values[i], values[i]);
				}
			
				list.forEach(function(elem, idx) {
					expect(elem).toBe(idx);
				});
			});
			
			it("returns same-priority elements in inserted order", function() {
				values = [0, 1, 2, 3, 4, 5, 6, 7, 8 , 9];
	
				for (var i in values) {
					list.insert(values[i], values[i]/3);
				}
			
				list.forEach(function(elem, idx) {
					expect(elem).toBe(idx);
				});
			});
		});
		
		describe("every()", function() {
			it("defaults to true", function() {
				expect(list.every(function() {})).toBe(true);
			});
		});
	});
});