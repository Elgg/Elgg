define(['jquery', 'elgg', 'sprintf'], function ($, elgg, sprintf) {

	var translations_data = [];
	
	return {
		/**
		 * Helper function to reset all registered translations. Mainly used for testing purposes.
		 */
		reset: function() {
			translations_data = [];
		},
		
		/**
		 * Initializes a language.
		 *
		 * @param {String} language The language key (defaults to the current language)
		 */
		initLanguage: function(language) {
			language = language || elgg.config.current_language;

			if (typeof translations_data[language] !== 'undefined') {
				// only init a language once
				return;
			}

			$.ajax({
				url: elgg.get_simplecache_url('languages/' + language + '.js'),
				dataType: 'json',
				async: false,
				success: function(translations) {
					translations_data[language] = translations;
				}
			});
		},
		
		/**
		 * Analogous to the php version. Merges translations for a
		 * given language into the current translations map.
		 *
		 * @param {String} lang         The language key
		 * @param {Array}  translations Array of translations
		 */
		addTranslation: function(lang, translations) {
			if (typeof translations_data[lang] === 'undefined') {
				translations_data[lang] = {};
			}
				
			$.extend(translations_data[lang], translations);
		},

		/**
		 * Translates a string
		 *
		 * @param {String} key      Message key
		 * @param {Array}  argv     vsprintf() arguments
		 * @param {String} language Requested language. Not recommended (see above).
		 *
		 * @return {String} The translation or the given key if no translation available
		 */
		echo: function(key, argv, language) {
			//echo('str', 'en')
			if (typeof argv === 'string') {
				language = argv;
				argv = [];
			}

			this.initLanguage(language);
			
			//echo('str', [...], 'en')
			var dlang = elgg.config.current_language;
		
			language = language || dlang;
			argv = argv || [];
		
			var map = translations_data[language] || translations_data[dlang];
			if (map && (typeof map[key] === 'string')) {
				return sprintf.vsprintf(map[key], argv);
			}
		
			return key;
		}
	};
});
