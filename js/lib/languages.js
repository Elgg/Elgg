/*globals vsprintf*/
/**
 * Provides language-related functionality
 */
elgg.provide('elgg.config.translations');

// If set to true, requested language keys will be stored in elgg._echoKeys and you'll
// get a console message if the "late" translations are requested.
elgg.config.language_debug = false;

// default language - required by unit tests
elgg.config.language = 'en';

// this is set just before the elgg module is defined and lets the elgg/echo module
// know which language module was depended on.
elgg.config.initial_language_module = null;
elgg.config.use_early_language = false;

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
	var user = elgg.get_logged_in_user_entity();

	if (user && user.language) {
		return user.language;
	}

	return elgg.config.language;
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
 * @param {Array}  argv     vsprintf() arguments
 * @param {String} language Requested language. Not recommended (see above).
 *
 * @return {String} The translation or the given key if no translation available
 * @deprecated Use the elgg/echo module
 */
elgg.echo = function(key, argv, language) {
	if (elgg.config.language_debug) {
		elgg._echoKeys = elgg._echoKeys || {sync:{},async:{}};
		elgg._echoKeys.sync[key] = true;
	}

	//elgg.echo('str', 'en')
	if (elgg.isString(argv)) {
		language = argv;
		argv = [];
	}

	if (elgg.is_admin_logged_in()) {
		// give example module syntax for usage
		var code = 'require(';
		var module = 'elgg/echo!' + key;
		if (language) {
			module += '!' + language;
		}
		code += JSON.stringify(module) + ')(';
		if (argv && argv.length) {
			code += JSON.stringify(argv);
		}
		code += ')';

		elgg.deprecated_notice("elgg.echo() is deprecated. Use elgg/echo in your module. E.g. " + code, "2.3");
	}

	//elgg.echo('str', [...], 'en')
	var translations = elgg.config.translations,
		dlang = elgg.get_language(),
		map;

	language = language || dlang;
	argv = argv || [];

	map = translations[language] || translations[dlang];
	if (map && elgg.isString(map[key])) {
		return vsprintf(map[key], argv);
	}

	return key;
};

