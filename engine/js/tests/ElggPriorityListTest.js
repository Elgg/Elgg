ElggPriorityListTest = TestCase("ElggPriorityListTest");

ElggPriorityListTest.prototype.setUp = function() {
	this.list = new elgg.ElggPriorityList();
};

ElggPriorityListTest.prototype.tearDown = function() {
	this.list = null;
};

ElggPriorityListTest.prototype.testInsert = function() {
	this.list.insert('foo');

	assertEquals('foo', this.list.priorities_[500][0]);

	this.list.insert('bar', 501);

	assertEquals('foo', this.list.priorities_[501][0]);
};

ElggPriorityListTest.prototype.testInsertRespectsPriority = function() {
	var values = [5, 4, 3, 2, 1, 0];

	for (var i in values) {
		this.list.insert(values[i], values[i]);
	}

	this.list.forEach(function(elem, idx)) {
		assertEquals(elem, idx);
	}
};

ElggPriorityListTest.prototype.testInsertHandlesDuplicatePriorities = function() {
	values = [0, 1, 2, 3, 4, 5, 6, 7, 8 , 9];

	for (var i in values) {
		this.list.insert(values[i], values[i]/3);
	}

	this.list.forEach(function(elem, idx) {
		assertEquals(elem, idx);
	});
};

ElggPriorityListTest.prototype.testEveryDefaultsToTrue = function() {
	assertTrue(this.list.every(function() {}));
};