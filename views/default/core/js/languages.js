/*globals vsprintf*/
/**
 * Provides language-related functionality
 */
elgg.provide('elgg.config.translations');

// default language - required by unit tests
elgg.config.language = 'en';

/**
 * Analagous to the php version.  Merges translations for a
 * given language into the current translations map.
 */
elgg.add_translation = function(lang, translations) {
	elgg.provide('elgg.config.translations.' + lang);

	elgg.extend(elgg.config.translations[lang], translations);
};

/**
 * Get the current language
 * @return {String}
 */
elgg.get_language = function() {
	// set by _elgg_get_js_page_data()
	return elgg.config.current_language;
};

/**
 * Translates a string
 *
 * @note The current system only loads a single language module per page, and it comes pre-merged with English
 *       translations. Hence, elgg.echo() can only return translations in the language returned by
 *       elgg.get_language(). Requests for other languages will fail unless a 3rd party plugin has manually
 *       used elgg.add_translation() to merge the language module ahead of time.
 *
 * @param {String} key      Message key
 * @param {Array}  args     vsprintf() arguments
 * @param {String} language Requested language. Not recommended (see above).
 *
 * @return {String} The translation or the given key if no translation available
 */
elgg.echo = function(key, args, language) {
	//elgg.echo('str', 'en')
	if (elgg.isString(args)) {
		language = args;
		args = [];
	}

	//elgg.echo('str', [...], 'en')
	var translations = elgg.config.translations,
		dlang = elgg.get_language(),
		map,
		string;

	language = language || dlang;
	args = args || {};

	map = translations[language] || translations[dlang];
	if (!map) {
		return key;
	}

	string = map[key];

	if (!elgg.isString(string)) {
		return key;
	}

	if (elgg.isArray(args)) {
		return vsprintf(string, args);
	}

	var argv = [], kwarg = {};

	for (var index in args) {
		if (!isNaN(index)) {
			argv.push(args[index]);
		} else {
			kwarg[index] = args[index];
		}
	}

	string = vsprintf(string, argv);

	var Mustache = require('mustache');
	string = Mustache.render(string, kwarg);

	return string;
};

