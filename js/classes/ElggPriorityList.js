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

	var index = 0, p, i, elems;
	for (p in this.priorities_) {
		elems = this.priorities_[p];
		for (i in elems) {
			callback(elems[i], index);
			index++;
		}
	}
};

/**
 *
 */
elgg.ElggPriorityList.prototype.every = function(callback) {
	elgg.assertTypeOf('function', callback);

	var index = 0, p, elems, i;

	for (p in this.priorities_) {
		elems = this.priorities_[p];
		for (i in elems) {
			if (!callback(elems[i], index)) {
				return false;
			}
			index++;
		}
	}

	return true;
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