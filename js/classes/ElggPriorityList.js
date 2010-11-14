/**
 *
 */
elgg.ElggPriorityList = function() {
	this.length = 0;
	this.priorities_ = [];
};

/**
 *
 */
elgg.ElggPriorityList.prototype.insert = function(obj, opt_priority) {
	var priority = parseInt(opt_priority || 500, 10);

	priority = Math.max(priority, 0);

	if (elgg.isUndefined(this.priorities_[priority])) {
		this.priorities_[priority] = [];
	}

	this.priorities_[priority].push(obj);
	this.length++;
};

/**
 *
 */
elgg.ElggPriorityList.prototype.forEach = function(callback) {
	elgg.assertTypeOf('function', callback);

	var index = 0;

	this.priorities_.forEach(function(elems) {
		elems.forEach(function(elem) {
			callback(elem, index++);
		});
	});

	return this;
};

/**
 *
 */
elgg.ElggPriorityList.prototype.every = function(callback) {
	elgg.assertTypeOf('function', callback);

	var index = 0;

	return this.priorities_.every(function(elems) {
		return elems.every(function(elem) {
			return callback(elem, index++);
		});
	});
};

/**
 *
 */
elgg.ElggPriorityList.prototype.remove = function(obj) {
	this.priorities_.forEach(function(elems) {
		var index;
		while ((index = elems.indexOf(obj)) !== -1) {
			elems.splice(index, 1);
			this.length--;
		}
	});
};