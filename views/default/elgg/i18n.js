define(['jquery', 'elgg', 'sprintf'], function ($, elgg, sprintf) {

	return {
		/**
		 * Initializes a language.
		 *
		 * @param {String} lang The language key (defaults to the current language)
		 */
		initLanguage: function(lang) {
			language = lang || elgg.config.current_language;

			if (typeof elgg.config.translations[language] !== 'undefined') {
				// only init a language once
				return;
			}

			$.ajax({
				url: elgg.get_simplecache_url('languages/' + language + '.js'),
				dataType: 'json',
				async: false,
				success: function(translations) {
					elgg.config.translations[language] = translations;
				}
			});
		},
		
		/**
		 * Analagous to the php version. Merges translations for a
		 * given language into the current translations map.
		 *
		 * @param {String} lang         The language key
		 * @param {Array}  translations Array of translations
		 */
		addTranslation: function(lang, translations) {
			if (typeof elgg.config.translations[lang] === 'undefined') {
				elgg.config.translations[lang] = {};
			}
				
			$.extend(elgg.config.translations[lang], translations);
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
			var translations = elgg.config.translations,
				dlang = elgg.config.current_language,
				map;
		
			language = language || dlang;
			argv = argv || [];
		
			map = translations[language] || translations[dlang];
			if (map && (typeof map[key] === 'string')) {
				return sprintf.vsprintf(map[key], argv);
			}
		
			return key;
		}
	};
});
