/*globals vsprintf*/
/**
 * Provides language-related functionality
 */
elgg.provide('elgg.config.translations');

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
 * Load the translations for the given language.
 *
 * If no language is specified, the default language is used.
 * @param {string} language
 * @return {XMLHttpRequest}
 */
elgg.reload_all_translations = function(language) {
	var lang = language || elgg.get_language();

	elgg.getJSON('ajax/view/js/languages', {
		data: {
			language: lang
		},
		success: function(json) {
			elgg.add_translation(lang, json);
		}
	});
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
 * @param {Array}  argv     vsprintf support
 * @param {String} language The language to display it in
 *
 * @return {String} The translation
 */
elgg.echo = function(key, argv, language) {
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

elgg.config.translations.init = function() {
	elgg.reload_all_translations();
};

elgg.register_hook_handler('boot', 'system', elgg.config.translations.init);