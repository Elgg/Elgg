/**
 * Create a new ElggUser
 *
 * @param {Object} o
 * @extends ElggEntity
 * @class Represents an ElggUser
 * @property {string} name
 * @property {string} username
 * @property {string} language
 * @property {boolean} admin
 */
elgg.ElggUser = function(o) {
	elgg.ElggEntity.call(this, o);
};

elgg.inherit(elgg.ElggUser, elgg.ElggEntity);

/**
 * Is this user an admin?
 *
 * @warning The admin state of the user should be checked on the server for any
 * actions taken that require admin privileges.
 *
 * @return {boolean}
 */
elgg.ElggUser.prototype.isAdmin = function() {
	return this.admin;
};