/**
 * Create a new ElggEntity
 * 
 * @class Represents an ElggEntity
 * @property {number} guid
 * @property {string} type
 * @property {string} subtype
 * @property {number} owner_guid
 * @property {number} site_guid
 * @property {number} container_guid
 * @property {number} access_id
 * @property {number} time_created
 * @property {number} time_updated
 * @property {number} last_action
 * @property {string} enabled
 * 
 */
elgg.ElggEntity = function(o) {
	$.extend(this, o);
};