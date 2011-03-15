/**
 * Interates through each element of an array and calls a callback function.
 * The callback should accept the following arguments:
 *	element - The current element
 *	index	- The current index
 *
 * This is different to Array.forEach in that if the callback returns false, the loop returns
 * immediately without processing the remaining elements.
 *
 *	@param {Function} callback
 *	@return {Bool}
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
 * Interates through each element of an array and calls callback a function.
 * The callback should accept the following arguments:
 *	element - The current element
 *	index	- The current index
 *
 * This is different to Array.every in that the callback's return value is ignored and every
 * element of the array will be parsed.
 *
 *	@param {Function} callback
 *	@return {Void}
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
 * Left trim
 *
 * Removes a character from the left side of a string.
 * @param {String} str The character to remove
 * @return {String}
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