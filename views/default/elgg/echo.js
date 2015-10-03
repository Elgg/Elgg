/**
 * Returns a "translator" function for an individual message key.
 *
 * - require('elgg/echo!{key}') returns a function that outputs the translation for {key} in
 * the default language.
 *
 * - require('elgg/echo!{key}@{lang}') returns a translator for language {lang}.
 *
 * The "elgg" module already loads the "languages/{lang}" module, so this module mostly provides a
 * future-ready asynchronous API to the already-loaded translations.
 */
define(function(require) {

	// TODO formalize dependency on vsprintf
	var elgg = require('elgg');

	var default_lang = elgg.get_language();

	function getTranslation(lang, key, callback) {
		// debug: uncomment the next two lines to capture which keys were requested
		//elgg._echoKeys = elgg._echoKeys || {sync:{},async:{}};
		//elgg._echoKeys.async[key] = true;

		var translations = null;
		if (elgg.config.translations && elgg.config.translations[lang]) {
			translations = elgg.config.translations[lang];
		}

		if (translations && translations.hasOwnProperty(key)) {
			callback(translations[key]);
			return;
		}

		if (elgg.config.initial_language_module == ('languages/' + lang)) {
			// we've loaded the full translation set and we just don't have this key
			callback(null);
			return;
		}

		/**
		 * In 3.0 "elgg" will depend on the much smaller "languages/early/{lang}" module initially, and this
		 * module will request the remaining "languages/late/{lang}" module only when needed.
		 *
		 * A site owner may enable the 3.0 behavior via an experimental flag in settings.php. (This mode
		 * cannot be officially supported, as some uses of elgg.echo() will certainly fail.)
		 */
		require(['languages/early/' + lang], function (map) {
			if (map.hasOwnProperty(key)) {
				callback(map[key]);
				// to benefit plugins still using elgg.echo, go ahead and merge these when we get them
				elgg.add_translation(lang, map);
			} else {
				require(['languages/late/' + lang], function (map) {
					// debug: uncomment the next line to see which key loaded the "late" translations
					//console.log('key "' + key + '" caused the late translations to load');

					callback(map.hasOwnProperty(key) ? map[key] : null);
					elgg.add_translation(lang, map);
				});
			}
		});
	}

	return { // loader plugin http://requirejs.org/docs/plugins.html#apiload

		// this function takes name and calls online when it has the return value
		load: function(name, req, onload, config) {

			// turn a translation string into a translator function and pass it
			// to online
			function makeTranslator(translation) {
				var f;

				if (null === translation) {
					f = function() {
						return name;
					};
					f.found = false;

				} else {
					f = function(args) {
						return vsprintf(translation, args || []);
					};
					f.found = true;
				}

				onload(f);
			}

			var lang = default_lang;
			var m = name.match(/^(.*)@([a-z_]+)$/i);
			if (m) {
				name = m[1];
				lang = m[2];
			}

			getTranslation(lang, name, function(translation) {
				if (translation !== null) {
					return makeTranslator(translation);
				}
				if (lang !== default_lang) {
					return getTranslation(default_lang, name, makeTranslator);
				}
				makeTranslator(null);
			});
		}
	};
});
