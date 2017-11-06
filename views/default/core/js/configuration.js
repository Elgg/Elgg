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
 * @param {String} view    The full view name
 * @param {String} subview If the first arg is "css" or "js", the rest of the view name
 * @return {String} The site URL.
 */
elgg.get_simplecache_url = function(view, subview) {
	var lastcache, path;

	if (elgg.config.simplecache_enabled) {
		lastcache = elgg.config.lastcache;
	} else {
		lastcache = 0;
	}

	if (!subview) {
		path = '/cache/' + lastcache + '/' + elgg.config.viewtype + '/' + view;
	} else {
		if ((view === 'js' || view === 'css') && 0 === subview.indexOf(view + '/')) {
			subview = subview.substr(view.length + 1);
		}
		path = '/cache/' + lastcache + '/' + elgg.config.viewtype + '/' + view + '/' + subview;
	}

	return elgg.normalize_url(path);
};
