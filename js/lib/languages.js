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
 * @param {String} key      Message key
 * @param {Array}  argv     vsprintf() arguments
 * @param {String} language Desired language
 *
 * @return {String} The translation or the given key if no translation available
 */
elgg.echo = function(key, argv, language) {

	// handle elgg.echo('str', 'en')
	if (elgg.isString(argv)) {
		language = argv;
		argv = [];
	} else {
		argv = argv || [];
	}

	language = language || elgg.get_language();

	var translations = elgg.config.translations,
		list = [language, elgg.get_language()],
		lang;

	while (lang = list.shift()) {
		if (translations[lang] && elgg.isString(translations[lang][key])) {
			return vsprintf(translations[lang][key], argv);
		}
	}

	return key;
};

