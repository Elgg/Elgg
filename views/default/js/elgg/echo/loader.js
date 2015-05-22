/**
 * Find a translation, loading translation packs as needed
 *
 * @internal do not use directly.
 */
define(function (require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	var config = require('elgg/echo/config');
	var url_prefix = elgg.get_site_url() + '_i18n/' + elgg.config.lastcache + '/';

	function pack_url(name, lang) {
		return url_prefix + lang + '/pack/' + name + '.js';
	}
	function key_url(key, lang) {
		// RFC 3986
		var encoded_key = encodeURIComponent(key).replace(/[!'()*]/g, function(c) {
			return '%' + c.charCodeAt(0).toString(16);
		});
		return url_prefix + lang + '/key/' + encoded_key + '.js';
	}

	var unloaded_packs = config.packs;
	var hasOwn = {}.hasOwnProperty;
	var packs = {
		loaded: {},

		is_loaded: function (name, lang) {
			return packs.loaded[name + '/' + lang] === true;
		},

		set_loaded: function (name, lang) {
			packs.loaded[name + '/' + lang] = true;
		},

		find_by_key: function (key) {
			// find pack
			var name, pack, key_string, i, l, regex, pattern, m;

			// search keys
			for (name in unloaded_packs) {
				if (hasOwn.call(unloaded_packs, name)) {
					key_string = unloaded_packs[name].keys.join(' ');
					if (key_string.indexOf(key) != -1) {
						return name;
					}
				}
			}

			// search patterns
			for (name in unloaded_packs) {
				if (hasOwn.call(unloaded_packs, name)) {
					for (i = 0, l = unloaded_packs[name].patterns.length; i < l; i++) {
						pattern = unloaded_packs[name].patterns[i];
						if (new RegExp(pattern[0], pattern[1]).test(key)) {
							return name;
						}
					}
				}
			}

			return "";
		},

		load: function(name, lang, cb) {
			if (packs.is_loaded(name, lang)) {
				cb();
			} else {
				elgg.getJSON(pack_url(name, lang)).done(function (pack) {
					$.each(pack, function (key, translation) {
						cache.set(key, lang, translation);
					});

					packs.set_loaded(name, lang);
					delete unloaded_packs[name];
					cb();
				});
			}
		}
	};

	var cache = {
		values: {},

		has: function(key, lang) {
			return cache.values[lang + '/' + key] !== undefined;
		},

		set: function(key, lang, translation) {
			cache.values[lang + '/' + key] = translation;
		},

		get: function(key, lang) {
			return cache.values[lang + '/' + key];
		}
	};

	/**
	 * Fetch a translation
	 *
	 * @param {String}   key  Translation key
	 * @param {String}   lang Language code
	 * @param {Function} cb   Callback will receive string translation or null
	 */
	return function(key, lang, cb) {
		if (cache.has(key, lang)) {
			cb(cache.get(key, lang));
		} else {
			var name = packs.find_by_key(key);
			if (name) {
				packs.load(name, lang, function () {
					var val = cache.has(key, lang) ? cache.get(key, lang) : null;
					cb(val);
				});
			} else {
				// load key
				elgg.getJSON(key_url(key, lang)).done(function (value) {
					var val = value === null ? key : value;
					cache.set(key, lang, val);
					cb(val);
				});
			}
		}
	};
});
