/**
 * @namespace Singleton object for holding the Elgg javascript library
 */
var elgg = elgg || {};

/**
 * Throw an exception of the type doesn't match
 */
elgg.assertTypeOf = function(type, val) {
	if (typeof val !== type) {
		throw new TypeError("Expecting param of " +
							arguments.caller + "to be a(n) " + type + "." +
							"  Was actually a(n) " + typeof val + ".");
	}
};

/**
 * Converts shorthand urls to absolute urls.
 *
 * If the url is already absolute or protocol-relative, no change is made.
 *
 * elgg.normalize_url('');                   // 'http://my.site.com/'
 * elgg.normalize_url('dashboard');          // 'http://my.site.com/dashboard'
 * elgg.normalize_url('http://google.com/'); // no change
 * elgg.normalize_url('//google.com/');      // no change
 *
 * @param {String} url The url to normalize
 * @return {String} The extended url
 */
elgg.normalize_url = function(url) {
	url = url || '';
	elgg.assertTypeOf('string', url);

	function validate(url) {
		url = elgg.parse_url(url);
		if (url.scheme) {
			url.scheme = url.scheme.toLowerCase();
		}
		if (url.scheme == 'http' || url.scheme == 'https') {
			if (!url.host) {
				return false;
			}
			/* hostname labels may contain only alphanumeric characters, dots and hypens. */
			if (!(new RegExp("^([a-zA-Z0-9][a-zA-Z0-9\\-\\.]*)$", "i")).test(url.host) || url.host.charAt(-1) == '.') {
				return false;
			}
		}
		/* some schemas allow the host to be empty */
		if (!url.scheme || !url.host && url.scheme != 'mailto' && url.scheme != 'news' && url.scheme != 'file') {
			return false;
		}
		return true;
	};

	// ignore anything with a recognized scheme
	if (url.indexOf('http:') === 0 || url.indexOf('https:') === 0 || url.indexOf('javascript:') === 0 || url.indexOf('mailto:') === 0 ) {
		return url;
	} else if (validate(url)) {
		// all normal URLs including mailto:
		return url;
	} else if ((new RegExp("^(\\#|\\?|//)", "i")).test(url)) {
		// '//example.com' (Shortcut for protocol.)
		// '?query=test', #target
		return url;
	} else if ((new RegExp("^[^\/]*\\.php(\\?.*)?$", "i")).test(url)) {
		// watch those double escapes in JS.
		// 'install.php', 'install.php?step=step'
		if (url.indexOf('/') === 0) {
			url = url.substring(1);
		}
		
		return elgg.config.wwwroot + url;
	} else if ((new RegExp("^[^/\\?\\#]*\\.", "i")).test(url)) {
		// 'example.com', 'example.com/subpage'
		return 'http://' + url;
	} else {
		// 'page/handler', 'mod/plugin/file.php'
		// trim off any leading / because the site URL is stored
		// with a trailing /
		if (url.indexOf('/') === 0) {
			url = url.substring(1);
		}
		
		return elgg.config.wwwroot + url;
	}
};

/**
 * Informs admin users via a console message about use of a deprecated function or capability
 *
 * @param {String} msg         The deprecation message to display
 * @param {String} dep_version The version the function was deprecated for
 * @since 1.9
 */
elgg.deprecated_notice = function(msg, dep_version) {
	if (elgg.is_admin_logged_in()) {
		msg = "Deprecated in Elgg " + dep_version + ": " + msg;
		if (typeof console !== "undefined") {
			console.info(msg);
		}
	}
};

/**
 * Meant to mimic the php forward() function by simply redirecting the
 * user to another page.
 *
 * @param {String} url The url to forward to
 */
elgg.forward = function(url) {
	var dest = elgg.normalize_url(url);

	if (dest == location.href) {
		location.reload();
	}

	// in case the href set below just changes the hash, we want to reload. There's sadly
	// no way to force a reload and set a different hash at the same time.
	$(window).on('hashchange', function () {
		location.reload();
	});

	location.href = dest;
};

/**
 * Parse a URL into its parts. Mimicks http://php.net/parse_url
 *
 * @param {String}  url       The URL to parse
 * @param {Number}  component A component to return
 * @param {Boolean} expand    Expand the query into an object? Else it's a string.
 *
 * @return {Object} The parsed URL
 */
elgg.parse_url = function(url, component, expand) {
	// Adapted from http://blog.stevenlevithan.com/archives/parseuri
	// which was release under the MIT
	// It was modified to fix mailto: and javascript: support.
	expand = expand || false;
	component = component || false;
	
	var re_str =
		// scheme (and user@ testing)
		'^(?:(?![^:@]+:[^:@/]*@)([^:/?#.]+):)?(?://)?'
		// possibly a user[:password]@
		+ '((?:(([^:@]*)(?::([^:@]*))?)?@)?'
		// host and port
		+ '([^:/?#]*)(?::(\\d*))?)'
		// path
		+ '(((/(?:[^?#](?![^?#/]*\\.[^?#/.]+(?:[?#]|$)))*/?)?([^?#/]*))'
		// query string
		+ '(?:\\?([^#]*))?'
		// fragment
		+ '(?:#(.*))?)';
	var keys = {
		1: "scheme",
		4: "user",
		5: "pass",
		6: "host",
		7: "port",
		9: "path",
		12: "query",
		13: "fragment"
	};
	var results = {};

	if (url.indexOf('mailto:') === 0) {
		results['scheme'] = 'mailto';
		results['path'] = url.replace('mailto:', '');
		return results;
	}

	if (url.indexOf('javascript:') === 0) {
		results['scheme'] = 'javascript';
		results['path'] = url.replace('javascript:', '');
		return results;
	}

	var re = new RegExp(re_str);
	var matches = re.exec(url);

	for (var i in keys) {
		if (matches[i]) {
			results[keys[i]] = matches[i];
		}
	}

	if (expand && typeof(results['query']) != 'undefined') {
		results['query'] = elgg.parse_str(results['query']);
	}

	if (component) {
		if (typeof(results[component]) != 'undefined') {
			return results[component];
		} else {
			return false;
		}
	}
	return results;
};

/**
 * Returns an object with key/values of the parsed query string.
 *
 * @param  {String} string The string to parse
 * @return {Object} The parsed object string
 */
elgg.parse_str = function(string) {
	var params = {},
		result,
		key,
		value,
		re = /([^&=]+)=?([^&]*)/g,
		re2 = /\[\]$/;

	// assignment intentional
	while (result = re.exec(string)) {
		key = decodeURIComponent(result[1].replace(/\+/g, ' '));
		value = decodeURIComponent(result[2].replace(/\+/g, ' '));

		if (re2.test(key)) {
			key = key.replace(re2, '');
			if (!params[key]) {
				params[key] = [];
			}
			params[key].push(value);
		} else {
			params[key] = value;
		}
	}
	
	return params;
};

/**
 * Returns a jQuery selector from a URL's fragment. Defaults to expecting an ID.
 *
 * Examples:
 *  http://elgg.org/download.php returns ''
 *	http://elgg.org/download.php#id returns #id
 *	http://elgg.org/download.php#.class-name return .class-name
 *	http://elgg.org/download.php#a.class-name return a.class-name
 *
 * @param {String} url The URL
 * @return {String} The selector
 */
elgg.getSelectorFromUrlFragment = function(url) {
	var fragment = url.split('#')[1];

	if (fragment) {
		// this is a .class or a tag.class
		if (fragment.indexOf('.') > -1) {
			return fragment;
		} else {
			// this is an id
			return '#' + fragment;
		}
	}
	return '';
};

/**
 * Returns the GUID of the logged in user or 0.
 *
 * @return {number} The GUID of the logged in user
 */
elgg.get_logged_in_user_guid = function() {
	return elgg.user ? elgg.user.guid : 0;
};

/**
 * Returns if a user is logged in.
 *
 * @return {boolean} Whether there is a user logged in
 */
elgg.is_logged_in = function() {
	return elgg.get_logged_in_user_guid() > 0;
};

/**
 * Returns if the currently logged in user is an admin.
 *
 * @return {boolean} Whether there is an admin logged in
 */
elgg.is_admin_logged_in = function() {
	return elgg.user ? elgg.user.admin : false;
};

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
	elgg.assertTypeOf('string', view);
	
	var lastcache, path;

	if (elgg.config.simplecache_enabled) {
		lastcache = elgg.config.lastcache;
	} else {
		lastcache = 0;
	}

	if (!subview) {
		path = '/cache/' + lastcache + '/' + elgg.config.viewtype + '/' + view;
	} else {
		elgg.assertTypeOf('string', subview);
		
		if ((view === 'js' || view === 'css') && 0 === subview.indexOf(view + '/')) {
			subview = subview.substr(view.length + 1);
		}
		path = '/cache/' + lastcache + '/' + elgg.config.viewtype + '/' + view + '/' + subview;
	}

	return elgg.normalize_url(path);
};
