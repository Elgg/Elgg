/**
 * Create a new ElggUser
 *
 * @param {Object} o
 * @extends ElggEntity
 * @class Represents an ElggUser
 * @property {string} name
 * @property {string} username
 */
elgg.ElggUser = function(o) {
	//elgg.ElggEntity.call(this, o);
	this = o;
};

//elgg.inherit(elgg.ElggUser, elgg.ElggEntity);

/**
 * @return {boolean} Whether the user is an admin
 */
elgg.ElggUser.prototype.isAdmin = function() {
	return this.admin === 'yes';
};