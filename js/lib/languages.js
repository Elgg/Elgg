/*globals vsprintf*/
/**
 * Provides language-related functionality
 */
elgg.provide('elgg.config.translations');

// default language - required by unit tests
elgg.config.language = 'en';

var lang_modules_loaded = {};

/**
 * Analagous to the php version.  Merges translations for a
 * given language into the current translations map.
 */
elgg.add_translation = function(lang, translations, is_module) {
	if (is_module) {
		if (lang_modules_loaded[lang]) {
			// don't bother
			return;
		} else {
			lang_modules_loaded[lang] = true;
		}
	}

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
 * Call a function once the user's translations have been loaded
 *
 * @param {Function} func The function
 */
elgg.echo_ready = function(func) {
	require(['languages/' + elgg.get_language()], function (translations) {
		elgg.add_translation(elgg.get_language(), translations, true);
		func && func();
	});
};

/**
 * Translates a string (assuming its translations have been loaded).
 *
 * @note Use elgg.echo_ready() to make sure the translations have been loaded.
 *
 * @param {String} key      The string to translate
 * @param {Array}  argv     vsprintf support
 * @param {String} language The language to display it in
 *
 * @return {String} The translation
 */
elgg.echo = function(key, argv, language) {
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
