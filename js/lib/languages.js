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
	var user = elgg.get_logged_in_user_entity();

	if (user && user.language) {
		return user.language;
	}

	return elgg.config.language;
};

/**
 * Translates a string
 *
 * @param {String|String[]} keys     The translation key. If an array of keys is given, the first key with an
 *                                   available translation will be used.
 * @param {Array}           argv     Arguments to pass through vsprintf().
 * @param {String}          language The desired language code (defaults to site/user default, then English).
 *
 * @return {String} Either the translated string, the English string, or the original translation key.
 */
elgg.echo = function(keys, argv, language) {
	//elgg.echo('str', 'en')
	if (elgg.isString(argv)) {
		language = argv;
		argv = [];
	}

	if (elgg.isString(keys)) {
		keys = [keys];
	}

	//elgg.echo('str', [...], 'en')
	var translations = elgg.config.translations,
		dlang = elgg.get_language(),
		map,
		i;

	language = language || dlang;
	argv = argv || [];

	map = translations[language] || translations[dlang];
	if (!map) {
		return keys.pop();
	}

	for (i = 0; i < keys.length; i++) {
		if (map[keys[i]]) {
			return vsprintf(map[keys[i]], argv);
		}
	}

	return keys.pop();
};
