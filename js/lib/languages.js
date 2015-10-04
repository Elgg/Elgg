/*globals vsprintf*/
/**
 * Provides language-related functionality
 */
elgg.provide('elgg.config.translations');

// default language - required by unit tests
elgg.config.language = 'en';

// this is set just before the elgg module is defined and lets the elgg/echo module
// know which language module was depended on.
elgg.config.initial_language_module = null;

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
 * @param {String} key      The string to translate
 * @param {Array}  argv     Arguments for vsprintf(). If this argument is given as a string, it's assumed to
 *                          specify the language and the 3rd argument is ignored.
 * @param {String} language The language to display it in
 *
 * @return {String} The translation
 * @deprecated Use the elgg/echo module
 */
elgg.echo = function(key, argv, language) {
	// debug: uncomment the next two lines to capture which keys were requested
	//elgg._echoKeys = elgg._echoKeys || {sync:{},async:{}};
	//elgg._echoKeys.sync[key] = true;

	elgg.deprecated_notice("elgg.echo() is deprecated. Use the elgg/echo module", "2.1");

	//elgg.echo('str', 'en')
	if (elgg.isString(argv)) {
		language = argv;
		argv = [];
	}

	//elgg.echo('str', [...], 'en')
	var translations = elgg.config.translations,
		dlang = elgg.get_language(),
		map;

	language = language || dlang;
	argv = argv || [];

	map = translations[language] || translations[dlang];
	if (map && map[key]) {
		return vsprintf(map[key], argv);
	}

	return key;
};

