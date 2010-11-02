elgg.ElggPriorityList = function() {
	this.length = 0;
	this.priorities_ = [];
};

elgg.ElggPriorityList.prototype.insert = function(obj, opt_priority) {
	if (opt_priority == undefined) {
		opt_priority = 500;
	} 

	opt_priority = parseInt(opt_priority);
	if (opt_priority < 0) {
		opt_priority = 0;
	}
	
	if (this.priorities_[opt_priority] == undefined) {
		this.priorities_[opt_priority] = [];
	}
	
	this.priorities_[opt_priority].push(obj);
	this.length++;
};

elgg.ElggPriorityList.prototype.forEach = function(callback) {
	elgg.assertTypeOf('function', callback);

	var index = 0;
	for (var p in this.priorities_) {
		var elems = this.priorities_[p];
		for (var i in elems) {
			callback(elems[i], index);
			index++;
		}
	}
};

elgg.ElggPriorityList.prototype.every = function(callback) {
	elgg.assertTypeOf('function', callback);
	
	var index = 0;
	for (var p in this.priorities_) {
		var elems = this.priorities_[p];
		for (var i in elems) {
			if (!callback(elems[i], index)) {
				return false;
			};
		}
	}
	
	return true;
};

elgg.ElggPriorityList.prototype.remove = function(obj) {
	this.priorities_.forEach(function(elems, priority) {
		var index;
		while ((index = elems.indexOf(obj)) != -1) {
			elems.splice(index, 1);
		}
	});
};