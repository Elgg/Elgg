elgg.provide('elgg.config');

/**
 * Returns the current site URL
 *
 * @return {String} The site URL.
 */
elgg.get_site_url = function() {
	return elgg.config.wwwroot;
};

/**
 * Get the URL for the cached file
 * 
 * @param {String} type
 * @param {String} view
 * @return {String} The site URL.
 */
elgg.get_simplecache_url = function(type, view) {
	var lastcache;
	if (elgg.config.simplecache_enabled) {
		lastcache = elgg.config.lastcache;
	} else {
		lastcache = Math.floor((new Date()).getTime()/1000);
	}
	var path = '/cache/'+lastcache+'/'+elgg.config.viewtype+'/'+type+'/'+view;
	return elgg.normalize_url(path);
}