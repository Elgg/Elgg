/**
 *
 */
if (!Array.prototype.every) {
	Array.prototype.every = function(callback) {
		var len = this.length, i;

		for (i = 0; i < len; i++) {
			if (i in this && !callback.call(null, this[i], i)) {
				return false;
			}
		}

		return true;
	};
}

/**
 *
 */
if (!Array.prototype.forEach) {
	Array.prototype.forEach = function(callback) {
		var len = this.length, i;

		for (i = 0; i < len; i++) {
			if (i in this) {
				callback.call(null, this[i], i);
			}
		}
	};
}

/**
 *
 */
if (!String.prototype.ltrim) {
	String.prototype.ltrim = function(str) {
		if (this.indexOf(str) === 0) {
			return this.substring(str.length);
		} else {
			return this;
		}
	};
}