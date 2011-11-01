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
	elgg.ElggEntity.call(this, o);
	elgg.extend(this, {
		isAdmin: function(){
			return this.admin;
		},
	});
};

elgg.inherit(elgg.ElggUser, elgg.ElggEntity);